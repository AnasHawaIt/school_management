<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Entities\Teacher;
use Modules\Academic\Entities\TeacherQualification;
use Modules\Core\Entities\User;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'first_name'     => 'Ahmad',
                'last_name'      => 'Hassan',
                'first_name_ar'  => 'أحمد',
                'last_name_ar'   => 'حسن',
                'gender'         => 'male',
                'specialization' => 'Mathematics',
                'email'          => 'ahmad.teacher@school.com',
                'qualification'  => ['title' => 'Bachelor of Mathematics', 'field' => 'Mathematics'],
            ],
            [
                'first_name'     => 'Sara',
                'last_name'      => 'Ali',
                'first_name_ar'  => 'سارة',
                'last_name_ar'   => 'علي',
                'gender'         => 'female',
                'specialization' => 'Arabic Language',
                'email'          => 'sara.teacher@school.com',
                'qualification'  => ['title' => 'Bachelor of Arabic Literature', 'field' => 'Arabic Language'],
            ],
            [
                'first_name'     => 'Omar',
                'last_name'      => 'Khalid',
                'first_name_ar'  => 'عمر',
                'last_name_ar'   => 'خالد',
                'gender'         => 'male',
                'specialization' => 'Science',
                'email'          => 'omar.teacher@school.com',
                'qualification'  => ['title' => 'Bachelor of Science', 'field' => 'Natural Sciences'],
            ],
            [
                'first_name'     => 'Maha',
                'last_name'      => 'Ibrahim',
                'first_name_ar'  => 'مها',
                'last_name_ar'   => 'إبراهيم',
                'gender'         => 'female',
                'specialization' => 'English Language',
                'email'          => 'maha.teacher@school.com',
                'qualification'  => ['title' => 'Bachelor of English Literature', 'field' => 'English Language'],
            ],
            [
                'first_name'     => 'Yousef',
                'last_name'      => 'Mahmoud',
                'first_name_ar'  => 'يوسف',
                'last_name_ar'   => 'محمود',
                'gender'         => 'male',
                'specialization' => 'Islamic Studies',
                'email'          => 'yousef.teacher@school.com',
                'qualification'  => ['title' => 'Bachelor of Islamic Studies', 'field' => 'Sharia'],
            ],
        ];

        foreach ($teachers as $index => $data) {
            $user = User::create([
                'name'     => "{$data['first_name']} {$data['last_name']}",
                'email'    => $data['email'],
                'password' => Hash::make('Teacher@123'),
                'user_type'     => 'teacher',
            ]);

            $teacher = Teacher::create([
                'user_id'          => $user->id,
                'employee_id'      => 'EMP-' . now()->year . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'first_name'       => $data['first_name'],
                'last_name'        => $data['last_name'],
                'first_name_ar'    => $data['first_name_ar'],
                'last_name_ar'     => $data['last_name_ar'],
                'gender'           => $data['gender'],
                'specialization'   => $data['specialization'],
                'experience_years' => rand(3, 15),
                'joining_date'     => now()->subYears(rand(1, 5))->format('Y-m-d'),
                'salary'           => rand(4000, 9000),
                'contract_type'    => 'full_time',
                'status'           => 'active',
            ]);

            TeacherQualification::create([
                'teacher_id'     => $teacher->id,
                'type'           => 'degree',
                'title'          => $data['qualification']['title'],
                'institution'    => 'University of Education',
                'field_of_study' => $data['qualification']['field'],
                'year_obtained'  => now()->year - rand(5, 15),
            ]);

            $user->assignRole('teacher');

            $this->command->info("✅ Teacher created: {$teacher->full_name} [{$teacher->employee_id}]");
        }
    }
}
