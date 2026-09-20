<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\app\Entities\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'روايات',
            'description' => 'الروايات الأدبية والقصص الطويلة.',
        ]);

        Category::create([
            'name' => 'علوم',
            'description' => 'الكتب العلمية والتقنية.',
        ]);

        Category::create([
            'name' => 'تاريخ',
            'description' => 'كتب التاريخ والحضارات.',
        ]);

        Category::create([
            'name' => 'أدب',
            'description' => 'الكتب الأدبية والشعرية.',
        ]);

        Category::create([
            'name' => 'برمجة',
            'description' => 'كتب البرمجة وعلوم الحاسوب.',
        ]);
    }
}
