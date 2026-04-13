<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\Grade;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\SchoolClass;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب السنة الدراسية الحالية
        $currentYear = AcademicYear::where('is_current', true)->first();

        if (!$currentYear) {
            $this->command->error('No current academic year found! Please run AcademicYearSeeder first.');
            return;
        }

        // 2. جلب جميع المراحل الدراسية
        $grades = Grade::all();

        foreach ($grades as $grade) {
            // إنشاء شعبتين لكل مرحلة: A و B
            $sections = ['A', 'B'];

            foreach ($sections as $section) {
                SchoolClass::updateOrCreate(
                    [
                        'grade_id' => $grade->id,
                        'academic_year_id' => $currentYear->id,
                        'name' => "{$grade->name} - {$section}",
                    ],
                    [
                        'max_students' => 25,
                        'description' => "Section {$section} for {$grade->name}",
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Classes (Sections A & B) for all grades have been seeded!');
    }
}
