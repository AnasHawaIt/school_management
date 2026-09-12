<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Author;
use Modules\Library\Entities\Category;
use Modules\Library\Entities\Publisher;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $author1 = Author::where('name', 'نجيب محفوظ')->firstOrFail();
        $author2 = Author::where('name', 'غسان كنفاني')->firstOrFail();
        $author3 = Author::where('name', 'أحمد خالد توفيق')->firstOrFail();

        $novels = Category::where('name', 'روايات')->firstOrFail();
        $literature = Category::where('name', 'أدب')->firstOrFail();

        $shorouk = Publisher::where('name', 'دار الشروق')->firstOrFail();
        $adab = Publisher::where('name', 'دار الآداب')->firstOrFail();

        Book::updateOrCreate(
            ['isbn' => '9789770901234'],
            [
                'title' => 'اللص والكلاب',
                'description' => 'رواية للكاتب نجيب محفوظ.',
                'author_id' => $author1->id,
                'category_id' => $novels->id,
                'publisher_id' => $shorouk->id,
            ]
        );

        Book::updateOrCreate(
            ['isbn' => '9789953894567'],
            [
            'title' => 'رجال في الشمس',
            'description' => 'رواية للكاتب غسان كنفاني.',
            'author_id' => $author2->id,
            'category_id' => $novels->id,
            'publisher_id' => $adab->id,
        ]);

        Book::updateOrCreate(
            ['isbn' => '9789770912345'],
            [
            'title' => 'يوتوبيا',
            'description' => 'رواية للكاتب أحمد خالد توفيق.',
            'author_id' => $author3->id,
            'category_id' => $novels->id,
            'publisher_id' => $shorouk->id,
        ]);
    }
}
