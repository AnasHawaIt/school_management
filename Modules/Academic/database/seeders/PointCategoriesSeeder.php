<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\PointCategory;

class PointCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ===== إيجابية =====
            [
                'name'           => 'Attendance',
                'name_ar'        => 'الحضور المنتظم',
                'type'           => 'positive',
                'default_points' => 2,
                'icon'           => 'check-circle',
                'color'          => '#10B981',
                'auto_assign'    => true,  // تلقائي من نظام الحضور
            ],
            [
                'name'           => 'Good Behavior',
                'name_ar'        => 'السلوك الحسن',
                'type'           => 'positive',
                'default_points' => 5,
                'icon'           => 'star',
                'color'          => '#F59E0B',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Academic Achievement',
                'name_ar'        => 'التفوق الأكاديمي',
                'type'           => 'positive',
                'default_points' => 10,
                'icon'           => 'academic-cap',
                'color'          => '#3B82F6',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Cleanliness',
                'name_ar'        => 'النظافة الشخصية',
                'type'           => 'positive',
                'default_points' => 3,
                'icon'           => 'sparkles',
                'color'          => '#06B6D4',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Participation',
                'name_ar'        => 'المشاركة الصفية',
                'type'           => 'positive',
                'default_points' => 3,
                'icon'           => 'hand-raised',
                'color'          => '#8B5CF6',
                'auto_assign'    => false,
            ],

            // ===== سلبية =====
            [
                'name'           => 'Absence',
                'name_ar'        => 'الغياب',
                'type'           => 'negative',
                'default_points' => 5,
                'icon'           => 'x-circle',
                'color'          => '#EF4444',
                'auto_assign'    => true,  // تلقائي من نظام الحضور
            ],
            [
                'name'           => 'Late',
                'name_ar'        => 'التأخر',
                'type'           => 'negative',
                'default_points' => 2,
                'icon'           => 'clock',
                'color'          => '#F97316',
                'auto_assign'    => true,  // تلقائي من نظام الحضور
            ],
            [
                'name'           => 'Bad Behavior',
                'name_ar'        => 'السلوك السيء',
                'type'           => 'negative',
                'default_points' => 8,
                'icon'           => 'exclamation-circle',
                'color'          => '#DC2626',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Uniform Violation',
                'name_ar'        => 'مخالفة الزي المدرسي',
                'type'           => 'negative',
                'default_points' => 3,
                'icon'           => 'x-mark',
                'color'          => '#9333EA',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Cleanliness Violation',
                'name_ar'        => 'عدم الالتزام بالنظافة',
                'type'           => 'negative',
                'default_points' => 3,
                'icon'           => 'trash',
                'color'          => '#78716C',
                'auto_assign'    => false,
            ],
            [
                'name'           => 'Cheating',
                'name_ar'        => 'الغش',
                'type'           => 'negative',
                'default_points' => 15,
                'icon'           => 'eye-slash',
                'color'          => '#991B1B',
                'auto_assign'    => false,
            ],
        ];

        foreach ($categories as $cat) {
            PointCategory::firstOrCreate(['name' => $cat['name']], $cat);
            $icon = $cat['type'] === 'positive' ? '✅' : '❌';
            $this->command->info("{$icon} فئة: {$cat['name_ar']} ({$cat['default_points']} نقطة)");
        }
    }
}
