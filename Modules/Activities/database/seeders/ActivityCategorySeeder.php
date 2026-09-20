<?php

namespace Modules\Activities\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Activities\app\Entities\ActivityCategory;

class ActivityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Educational',
                'name_ar' => 'تعليمي',
                'description' => 'Educational and academic activities',
                'icon' => 'book-open',
                'color' => '#2563EB',
                'sort_order' => 1,
            ],
            [
                'name' => 'Sports',
                'name_ar' => 'رياضي',
                'description' => 'Sports and physical activities',
                'icon' => 'trophy',
                'color' => '#16A34A',
                'sort_order' => 2,
            ],
            [
                'name' => 'Cultural',
                'name_ar' => 'ثقافي',
                'description' => 'Cultural and artistic activities',
                'icon' => 'palette',
                'color' => '#9333EA',
                'sort_order' => 3,
            ],
            [
                'name' => 'Trips',
                'name_ar' => 'رحلات',
                'description' => 'School trips and visits',
                'icon' => 'map',
                'color' => '#EA580C',
                'sort_order' => 4,
            ],
            [
                'name' => 'Competitions',
                'name_ar' => 'مسابقات',
                'description' => 'Competitions and challenges',
                'icon' => 'medal',
                'color' => '#CA8A04',
                'sort_order' => 5,
            ],
            [
                'name' => 'Social',
                'name_ar' => 'اجتماعي',
                'description' => 'Social and community activities',
                'icon' => 'users',
                'color' => '#DB2777',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            ActivityCategory::updateOrCreate(
                [
                    'name' => $category['name'],
                ],
                $category
            );
        }
    }
}
