<?php

namespace Tests\Feature\Library;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\User;
use Modules\Library\Entities\Author;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Category;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Publishers;
use Tests\TestCase;

class LibraryLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_and_circulation_entities_support_delete_restore_and_force_delete(): void
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $member = Member::create([
            'user_id' => $user->id,
            'membership_number' => 'MEM-LIFECYCLE',
            'start_date' => today(),
            'end_date' => today()->addYear(),
            'status' => 'active',
        ]);
        $author = Author::create(['name' => 'Lifecycle Author', 'birth_date' => '1980-01-01']);
        $category = Category::create(['name' => 'Lifecycle Category']);
        $publisher = Publishers::create(['name' => 'Lifecycle Publisher']);
        $book = Book::create([
            'title' => 'Lifecycle Book',
            'description' => 'Lifecycle test',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'publisher_id' => $publisher->id,
            'isbn' => 'LIFECYCLE-ISBN',
            'copies' => 0,
        ]);
        $borrowing = Borrowing::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'borrow_date' => today(),
            'status' => 'returned',
        ]);

        foreach ([$borrowing, $book, $member, $author, $category, $publisher] as $entity) {
            $entity->delete();
            $this->assertSoftDeleted($entity->getTable(), ['id' => $entity->id]);
            $entity->restore();
            $this->assertDatabaseHas($entity->getTable(), ['id' => $entity->id, 'deleted_at' => null]);
            $entity->delete();
            $entity->forceDelete();
            $this->assertDatabaseMissing($entity->getTable(), ['id' => $entity->id]);
        }
    }
}
