<?php

namespace Modules\School\database\seeders;

use App\Entities\Teacher;
use Illuminate\Database\Seeder;
use Modules\School\Entities\SchoolClass;
use Modules\School\Entities\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::where('is_active', true)->get();

        if ($classes->isEmpty()) {
            $this->command->warn('No Classes found! Please run ClassSeeder first.');
            return;
        }

        $teachers = Teacher::where('status', 'active')->get();

        if ($teachers->isEmpty()) {
            $this->command->warn('No active teachers found! Please run TeacherSeeder first.');
            return;
        }

        $teacherIndex = 0;

        foreach ($classes as $class) {

            $sections = [
                [
                    'name' => 'A',
                    'room' => 'R-' . $class->id . '01',
                ],
                [
                    'name' => 'B',
                    'room' => 'R-' . $class->id . '02',
                ],
            ];

            foreach ($sections as $sectionData) {

                $teacher = $teachers[$teacherIndex % $teachers->count()];
                $teacherIndex++;

                Section::updateOrCreate(
                    [
                        'class_id' => $class->id,
                        'name' => $sectionData['name'],
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'max_students' => $class->max_students ?? 30,
                        'current_students' => 0,
                        'room_number' => $sectionData['room'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Sections (A & B) have been created for all classes!');
    }
}
