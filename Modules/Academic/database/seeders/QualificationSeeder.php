<?php

namespace Modules\Academic\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\Teacher;
use Illuminate\Support\Facades\DB;

class

QualificationSeeder extends Seeder
{
    public function run(): void
    {
        // جلب أول مدرس موجود في النظام
        $teacher = DB::table('teachers')->first();

        if (!$teacher) {
            $this->command->warn('No teachers found! Please run TeacherSeeder first.');
            return;
        }

        $qualifications = [
            [
                'teacher_id' => $teacher->id,
                'type' => 'degree',
                'title' => 'Bachelor of Mathematics',
                'institution' => 'Damascus University',
                'field_of_study' => 'Mathematics & Statistics',
                'year_obtained' => 2015,
                'description' => 'Specialized in pure mathematics.',
            ],
            [
                'teacher_id' => $teacher->id,
                'type' => 'certificate',
                'title' => 'Advanced Pedagogy Certificate',
                'institution' => 'Global Education Institute',
                'field_of_study' => 'Teaching Methods',
                'year_obtained' => 2020,
                'expiry_date' => '2030-12-31',
                'description' => 'Professional certification for modern teaching.',
            ],
            [
                'teacher_id' => $teacher->id,
                'type' => 'training',
                'title' => 'Digital Classroom Management',
                'institution' => 'EdTech Solutions',
                'field_of_study' => 'Technology in Education',
                'year_obtained' => 2023,
                'description' => 'Training on using smart boards and LMS systems.',
            ],
        ];

        foreach ($qualifications as $qual) {
            DB::table('teacher_qualifications')->updateOrInsert(
                [
                    'teacher_id' => $qual['teacher_id'],
                    'title' => $qual['title']
                ],
                $qual + ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->command->info('Teacher qualifications seeded successfully!');
    }
}
