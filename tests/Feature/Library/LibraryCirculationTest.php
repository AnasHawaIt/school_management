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
        $user = User::factory()->create();
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
        $user = User::factory()->create();
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
