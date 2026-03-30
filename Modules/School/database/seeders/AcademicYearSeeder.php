<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            [
                'name' => '2024-2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-06-30',
                'is_current' => true,
                'is_active' => true,
                'description' => 'Current academic year',
            ],
            [
                'name' => '2023-2024',
                'start_date' => '2023-09-01',
                'end_date' => '2024-06-30',
                'is_current' => false,
                'is_active' => false,
                'description' => 'Previous academic year',
            ],
        ];

        foreach ($years as $year) {
            AcademicYear::firstOrCreate(
                ['name' => $year['name']],
                $year
            );
        }

        $this->command->info('Academic years seeded successfully!');
    }
}
