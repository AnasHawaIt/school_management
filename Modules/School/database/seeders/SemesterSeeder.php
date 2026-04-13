<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\Semester;
use Modules\School\Entities\AcademicYear;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        // جلب السنة الدراسية الحالية
        $currentYear = AcademicYear::where('is_current', true)->first();

        if (!$currentYear) {
            $this->command->error('No current academic year found! Please run AcademicYearSeeder first.');
            return;
        }

        $semesters = [
            [
                'academic_year_id' => $currentYear->id,
                'name' => 'First Semester',
                'order' => 1,
                'start_date' => $currentYear->start_date, // تبدأ مع بداية السنة
                'end_date' => date('Y-m-d', strtotime($currentYear->start_date . ' +4 months')),
                'is_current' => true,
                'is_active' => true,
            ],
            [
                'academic_year_id' => $currentYear->id,
                'name' => 'Second Semester',
                'order' => 2,
                'start_date' => date('Y-m-d', strtotime($currentYear->start_date . ' +5 months')),
                'end_date' => $currentYear->end_date, // تنتهي مع نهاية السنة
                'is_current' => false,
                'is_active' => true,
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::updateOrCreate(
                [
                    'academic_year_id' => $semester['academic_year_id'],
                    'name' => $semester['name']
                ],
                $semester
            );
        }

        $this->command->info('Semesters for ' . $currentYear->name . ' seeded successfully!');
    }
}
