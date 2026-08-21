<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Author;
use Modules\Library\Entities\Category;
use Modules\Library\Entities\Publishers;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $author1 = Author::where('name', 'نجيب محفوظ')->first();
        $author2 = Author::where('name', 'غسان كنفاني')->first();
        $author3 = Author::where('name', 'أحمد خالد توفيق')->first();

        $novels = Category::where('name', 'روايات')->first();
        $literature = Category::where('name', 'أدب')->first();

        $shorouk = Publishers::where('name', 'دار الشروق')->first();
        $adab = Publishers::where('name', 'دار الآداب')->first();

        Book::create([
            'title' => 'اللص والكلاب',
            'description' => 'رواية للكاتب نجيب محفوظ.',
            'author_id' => $author1->id,
            'category_id' => $novels->id,
            'publisher_id' => $shorouk->id,
            'isbn' => '9789770901234',
            'copies' => 10,
        ]);

        Book::create([
            'title' => 'رجال في الشمس',
            'description' => 'رواية للكاتب غسان كنفاني.',
            'author_id' => $author2->id,
            'category_id' => $novels->id,
            'publisher_id' => $adab->id,
            'isbn' => '9789953894567',
            'copies' => 8,
        ]);

        Book::create([
            'title' => 'يوتوبيا',
            'description' => 'رواية للكاتب أحمد خالد توفيق.',
            'author_id' => $author3->id,
            'category_id' => $novels->id,
            'publisher_id' => $shorouk->id,
            'isbn' => '9789770912345',
            'copies' => 12,
        ]);
    }
}
