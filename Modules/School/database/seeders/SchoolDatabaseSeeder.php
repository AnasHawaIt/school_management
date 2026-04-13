<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            AcademicYearSeeder::class, // السنة الدراسية أولاً
            GradeSeeder::class,        // الصفوف (1-12) ثانياً

            SemesterSeeder::class,     // الفصول الدراسية (تعتمد على AcademicYear)
            HolidaySeeder::class,      // العطلات (تعتمد على AcademicYear)

            ClassSeeder::class,        // المجموعات الدراسية (تعتمد على Grade + AcademicYear)
            SectionSeeder::class,      // الشعب (تعتمد على Class)
        ]);

        $this->command->info('--- School Module Seeded Successfully! ---');
    }
}
