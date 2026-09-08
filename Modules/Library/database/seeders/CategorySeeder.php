<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Entities\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'روايات', 'description' => 'الروايات الأدبية والقصص الطويلة.'],
            ['name' => 'علوم', 'description' => 'الكتب العلمية والطبيعية.'],
            ['name' => 'تاريخ', 'description' => 'كتب التاريخ والحضارات.'],
            ['name' => 'أدب', 'description' => 'الأدب والشعر والنقد.'],
            ['name' => 'برمجة', 'description' => 'البرمجة وعلوم الحاسوب.'],
            ['name' => 'فلسفة', 'description' => 'الفلسفة والفكر الإنساني.'],
            ['name' => 'تنمية ذاتية', 'description' => 'التطوير الشخصي والمهني.'],
            ['name' => 'أطفال', 'description' => 'كتب الأطفال واليافعين.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
