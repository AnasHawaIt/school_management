<?php

namespace Tests\Feature\Library;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Library\Entities\Author;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Category;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Publishers;
use Tests\TestCase;

class LibraryCirculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_and_return_a_book_copy_loan(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = Member::create([
            'user_id' => $user->id,
            'membership_number' => 'MEM-001',
            'start_date' => today(),
            'end_date' => today()->addYear(),
            'status' => 'active',
        ]);
        $book = $this->createBook(1);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-001',
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->postJson('/api/library/transactions', [
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'borrow_date' => today()->toDateString(),
            ])
            ->assertCreated();

        $this->assertDatabaseHas('transactions', [
            'book_id' => $book->id,
            'copy_id' => $copy->id,
            'status' => 'borrowed',
        ]);
        $this->assertDatabaseHas('library_copies', [
            'id' => $copy->id,
            'status' => 'borrowed',
        ]);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'copies' => 0]);

        $transactionId = \DB::table('transactions')->value('id');
        $this->actingAs($user)
            ->postJson("/api/library/transactions/{$transactionId}", [
                'status' => 'returned',
            ])
            ->assertOk();

        $this->assertDatabaseHas('library_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'copies' => 1]);
    }

    public function test_copy_from_another_book_cannot_be_borrowed(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = Member::create([
            'user_id' => $user->id,
            'membership_number' => 'MEM-002',
            'start_date' => today(),
            'end_date' => today()->addYear(),
            'status' => 'active',
        ]);
        $book = $this->createBook(1);
        $otherBook = $this->createBook(1, 'Other Book');
        $copy = BookCopy::create([
            'book_id' => $otherBook->id,
            'barcode' => 'BC-002',
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->postJson('/api/library/transactions', [
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'borrow_date' => today()->toDateString(),
            ])
            ->assertUnprocessable();
    }

    public function test_unavailable_copy_cannot_be_borrowed(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = $this->createMember($user, 'MEM-003');
        $book = $this->createBook(1);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-003',
            'status' => 'maintenance',
        ]);

        $this->actingAs($user)
            ->postJson('/api/library/transactions', [
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'borrow_date' => today()->toDateString(),
            ])
            ->assertUnprocessable();
    }

    public function test_expired_member_cannot_borrow(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = $this->createMember($user, 'MEM-004', today()->subDay());
        $book = $this->createBook(1);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-004',
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->postJson('/api/library/transactions', [
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'borrow_date' => today()->toDateString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('member_id');
    }

    public function test_overdue_processing_creates_and_settles_a_fine(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = $this->createMember($user, 'MEM-005');
        $book = $this->createBook(1);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-005',
            'status' => 'available',
        ]);

        $this->actingAs($user)
            ->postJson('/api/library/transactions', [
                'book_id' => $book->id,
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'borrow_date' => today()->subDays(5)->toDateString(),
                'due_date' => today()->subDays(2)->toDateString(),
            ])
            ->assertCreated();

        $this->artisan('library:check-overdue')->assertSuccessful();

        $this->assertDatabaseHas('library_fines', [
            'status' => 'unpaid',
            'amount' => 2,
        ]);

        $fineId = \DB::table('library_fines')->value('id');
        $this->actingAs($user)
            ->patchJson("/api/library/fines/{$fineId}", [
                'status' => 'paid',
                'notes' => 'Paid at circulation desk',
            ])
            ->assertOk();

        $this->assertDatabaseHas('library_fines', [
            'id' => $fineId,
            'status' => 'paid',
        ]);
    }

    public function test_active_loan_can_be_renewed_until_the_limit(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = $this->createMember($user, 'MEM-006');
        $book = $this->createBook(1);
        $copy = BookCopy::create(['book_id' => $book->id, 'barcode' => 'BC-006', 'status' => 'available']);

        $this->actingAs($user)->postJson('/api/library/transactions', [
            'book_id' => $book->id,
            'copy_id' => $copy->id,
            'member_id' => $member->id,
            'borrow_date' => today()->toDateString(),
        ])->assertCreated();

        $transactionId = \DB::table('transactions')->value('id');
        $this->actingAs($user)
            ->postJson("/api/library/transactions/{$transactionId}/renew")
            ->assertOk()
            ->assertJsonPath('data.renewal_count', 1);
    }

    public function test_reservation_is_created_for_unavailable_book_and_can_be_cancelled(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = $this->createMember($user, 'MEM-007');
        $book = $this->createBook(0);

        $response = $this->actingAs($user)->postJson('/api/library/reservations', [
            'book_id' => $book->id,
            'member_id' => $member->id,
        ])->assertCreated();

        $reservationId = $response->json('id');
        if (! $reservationId) {
            $reservationId = $response->json('data.id');
        }

        $this->actingAs($user)
            ->postJson("/api/library/reservations/{$reservationId}/cancel")
            ->assertOk()
            ->assertJsonPath('status', 'cancelled');

        $this->assertDatabaseHas('library_reservations', [
            'id' => $reservationId,
            'status' => 'cancelled',
        ]);
    }

    private function createMember(User $user, string $number, $endDate = null): Member
    {
        return Member::create([
            'user_id' => $user->id,
            'membership_number' => $number,
            'start_date' => today()->subYear(),
            'end_date' => $endDate ?? today()->addYear(),
            'status' => 'active',
        ]);
    }

    private function createBook(int $copies, string $title = 'Test Book'): Book
    {
        $author = Author::create(['name' => 'Author '.uniqid(), 'birth_date' => '1980-01-01']);
        $category = Category::create(['name' => 'Category '.uniqid()]);
        $publisher = Publishers::create(['name' => 'Publisher '.uniqid()]);

        return Book::create([
            'title' => $title,
            'description' => 'Test description',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'publisher_id' => $publisher->id,
            'isbn' => 'ISBN-'.uniqid(),
            'copies' => $copies,
        ]);
    }
}
