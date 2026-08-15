<?php

namespace Modules\School\database\seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            [
                'name' => '2025-2026',
                'start_date' => '2025-09-01',
                'end_date' => '2026-06-30',
                'is_current' => true,
                'is_active' => true,
                'description' => 'السنة الدراسية الحالية',
            ],
            [
                'name' => '2024-2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-06-30',
                'is_current' => false,
                'is_active' => true,
                'description' => 'السنة الدراسية السابقة',
            ],
        ];

        foreach ($years as $year) {
            // استخدام updateOrCreate يضمن تحديث البيانات إذا كانت موجودة مسبقاً
            AcademicYear::updateOrCreate(
                ['name' => $year['name']],
                $year
            );
        }

        $this->command->info('Academic years seeded successfully for 2025-2026!');
    }
}
