<?php

namespace Modules\Academic\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\app\Entities\Subject;
use Modules\School\Entities\Grade;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = Grade::all();

        if ($grades->isEmpty()) {
            $this->command->warn('⚠️  No grades found. Run School module seeder first.');
            return;
        }

        $subjects = [
            ['code' => 'MATH', 'name' => 'Mathematics',     'name_ar' => 'رياضيات',            'weekly_hours' => 5, 'color' => '#3B82F6'],
            ['code' => 'ARB',  'name' => 'Arabic Language',  'name_ar' => 'اللغة العربية',      'weekly_hours' => 6, 'color' => '#10B981'],
            ['code' => 'ENG',  'name' => 'English Language', 'name_ar' => 'اللغة الإنجليزية',   'weekly_hours' => 4, 'color' => '#F59E0B'],
            ['code' => 'SCI',  'name' => 'Science',          'name_ar' => 'علوم',               'weekly_hours' => 4, 'color' => '#8B5CF6'],
            ['code' => 'ISL',  'name' => 'Islamic Studies',  'name_ar' => 'تربية إسلامية',      'weekly_hours' => 3, 'color' => '#06B6D4'],
            ['code' => 'SOC',  'name' => 'Social Studies',   'name_ar' => 'دراسات اجتماعية',    'weekly_hours' => 3, 'color' => '#EF4444'],
        ];

        foreach ($grades as $grade) {
            foreach ($subjects as $subject) {
                // منطق استثناء: مثلاً لا تضف دراسات اجتماعية للصفوف العليا إذا كان هناك تخصص
                if ($grade->order > 9 && $subject['code'] === 'SOC') {
                    continue;
                }
                Subject::firstOrCreate(
                    ['code' => $subject['code'] . '-G' . $grade->id],
                    [
                        'name'         => $subject['name'],
                        'grade_id'     => $grade->id,
                        'weekly_hours' => $subject['weekly_hours'],
                        'credit_hours' => 1,
                        'pass_mark'    => $grade->order > 9 ? 60.00 : 50.00,                        'full_mark'    => 100.00,
                        'is_mandatory' => true,
                        'color'        => $subject['color'],
                        'status'       => 'active',
                    ]
                );
            }
            $this->command->info("✅ StudentPoints created for grade: {$grade->name}");
        }
    }
}
