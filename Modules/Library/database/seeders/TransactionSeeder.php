<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\app\Entities\Book;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Entities\Member;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $member1 = Member::where('membership_number', 'LIB-0001')->first();
        $member2 = Member::where('membership_number', 'LIB-0002')->first();

        $book1 = Book::where('isbn', '9789770901234')->first();
        $book2 = Book::where('isbn', '9789953894567')->first();

        Borrowing::create([
            'member_id' => $member1->id,
            'book_id' => $book1->id,
            'borrow_date' => now()->subDays(5)->toDateString(),
            'return_date' => null,
            'status' => 'borrowed',
        ]);

        Borrowing::create([
            'member_id' => $member2->id,
            'book_id' => $book2->id,
            'borrow_date' => now()->subDays(15)->toDateString(),
            'return_date' => now()->subDays(5)->toDateString(),
            'status' => 'returned',
        ]);
    }
}
