<?php

namespace Modules\Library\database\seeders;
use Illuminate\Database\Seeder;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;

class BookCopySeeder extends Seeder
{
    public function run(): void
    {
        $book1 = Book::where('isbn', '9789770901234')->firstOrFail();
        $book2 = Book::where('isbn', '9789953894567')->firstOrFail();
        $book3 = Book::where('isbn', '9789770912345')->firstOrFail();

        foreach (range(1, 10) as $number) {
            BookCopy::firstOrCreate(
                [
                    'barcode' => 'LIB-BOOK1-' . str_pad($number, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'book_id' => $book1->id,
                    'status' => 'available',
                    'location' => 'A-01',
                    'replacement_cost' => 10,
                ]
            );
        }

        foreach (range(1, 8) as $number) {
            BookCopy::firstOrCreate(
                [
                    'barcode' => 'LIB-BOOK2-' . str_pad($number, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'book_id' => $book2->id,
                    'status' => 'available',
                    'location' => 'A-02',
                    'replacement_cost' => 12,
                ]
            );
        }

        foreach (range(1, 18) as $number) {
            BookCopy::firstOrCreate(
                [
                    'barcode' => 'LIB-BOOK3-' . str_pad($number, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'book_id' => $book3->id,
                    'status' => 'available',
                    'location' => 'A-03',
                    'replacement_cost' => 15,
                ]
            );
        }
    }
}
