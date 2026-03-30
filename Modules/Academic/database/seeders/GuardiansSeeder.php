<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Entities\Guardian;
use Modules\Academic\Entities\Student;
use Modules\Core\Entities\User;

class GuardiansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('user')->take(8)->get();

        if ($students->isEmpty()) {
            $this->command->warn('⚠️  No students found. Run StudentsSeeder first.');
            return;
        }

        $guardianData = [
            ['first_name' => 'Ahmed',    'last_name' => 'Mohammed', 'first_name_ar' => 'أحمد',    'last_name_ar' => 'محمد',  'occupation' => 'Engineer'],
            ['first_name' => 'Hussein',  'last_name' => 'Hassan',   'first_name_ar' => 'حسين',    'last_name_ar' => 'حسن',   'occupation' => 'Doctor'],
            ['first_name' => 'Saeed',    'last_name' => 'Omar',     'first_name_ar' => 'سعيد',    'last_name_ar' => 'عمر',   'occupation' => 'Teacher'],
            ['first_name' => 'Rami',     'last_name' => 'Ali',      'first_name_ar' => 'رامي',    'last_name_ar' => 'علي',   'occupation' => 'Accountant'],
            ['first_name' => 'Tariq',    'last_name' => 'Salem',    'first_name_ar' => 'طارق',    'last_name_ar' => 'سالم',  'occupation' => 'Businessman'],
            ['first_name' => 'Walid',    'last_name' => 'Youssef',  'first_name_ar' => 'وليد',    'last_name_ar' => 'يوسف',  'occupation' => 'Lawyer'],
            ['first_name' => 'Faisal',   'last_name' => 'Nasser',   'first_name_ar' => 'فيصل',    'last_name_ar' => 'ناصر',  'occupation' => 'Manager'],
            ['first_name' => 'Khaled',   'last_name' => 'Mahmoud',  'first_name_ar' => 'خالد',    'last_name_ar' => 'محمود', 'occupation' => 'Government Employee'],
        ];

        foreach ($students as $index => $student) {
            $gData = $guardianData[$index];

            $user = User::create([
                'name'     => "{$gData['first_name']} {$gData['last_name']}",
                'email'    => strtolower($gData['first_name']) . '.parent' . ($index + 1) . '@school.com',
                'password' => Hash::make('Parent@123'),
                'user_type'     => 'parent',
            ]);

            $guardian = Guardian::create([
                'user_id'        => $user->id,
                'first_name'     => $gData['first_name'],
                'last_name'      => $gData['last_name'],
                'first_name_ar'  => $gData['first_name_ar'],
                'last_name_ar'   => $gData['last_name_ar'],
                'gender'         => 'male',
                'phone'          => '05' . rand(10000000, 99999999),
                'occupation'     => $gData['occupation'],
                'education_level'=> 'bachelor',
                'status'         => 'active',
            ]);

            $guardian->students()->attach($student->id, [
                'relationship'       => 'father',
                'is_primary_contact' => true,
                'can_pickup'         => true,
            ]);

            $user->assignRole('parent');

            $this->command->info("✅ Guardian created: {$guardian->full_name} → linked to [{$student->full_name}]");
        }
    }
}
