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
        // جلب الطلاب مع مستخدميهم للتأكد من وجود بيانات للربط
        $students = Student::with('user')->take(8)->get();

        if ($students->isEmpty()) {
            $this->command->warn('⚠️  No students found. Run StudentsSeeder first.');
            return;
        }

        $guardianData = [
            ['first' => 'Ahmed',   'last' => 'Mohammed', 'occ' => 'Engineer'],
            ['first' => 'Hussein', 'last' => 'Hassan',   'occ' => 'Doctor'],
            ['first' => 'Saeed',   'last' => 'Omar',     'occ' => 'Teacher'],
            ['first' => 'Rami',    'last' => 'Ali',      'occ' => 'Accountant'],
            ['first' => 'Tariq',   'last' => 'Salem',    'occ' => 'Businessman'],
            ['first' => 'Walid',   'last' => 'Youssef',  'occ' => 'Lawyer'],
            ['first' => 'Faisal',  'last' => 'Nasser',   'occ' => 'Manager'],
            ['first' => 'Khaled',  'last' => 'Mahmoud',  'occ' => 'Government Employee'],
        ];

        foreach ($students as $index => $student) {
            $gData = $guardianData[$index];

            // 1. إنشاء حساب المستخدم (باستخدام الحقول المفصلة للأسماء)
            $user = User::create([
                'first_name' => $gData['first'],
                'last_name'  => $gData['last'],
                'email'      => strtolower($gData['first']) . '.parent' . ($index + 1) . '@school.com',
                'password'   => Hash::make('Parent@123'),
                'user_type'  => 'parent',
                'is_active'  => true,
            ]);

            // 2. إنشاء بيانات ولي الأمر (بناءً على الميجريشن الأصلي للـ parents)
            $guardian = Guardian::create([
                'user_id'         => $user->id,
                'national_id'     => '10' . rand(10000000, 99999999),
                'phone_secondary' => '05' . rand(10000000, 99999999),
                'occupation'      => $gData['occ'],
                'education_level' => 'bachelor',
                'status'          => 'active',
            ]);

            // 3. ربط ولي الأمر بالطالب عبر جدول student_parent (الـ Pivot)
            $guardian->students()->attach($student->id, [
                'relationship'       => 'father',
                'is_primary_contact' => true,
                'can_pickup'         => true,
                'created_at'         => now(),
            ]);

            // إسناد الدور (Role) للمستخدم
            $user->assignRole('parent');

            $this->command->info("✅ Guardian: {$user->first_name} {$user->last_name} → Linked to Student: [{$student->first_name}]");
        }
    }
}
