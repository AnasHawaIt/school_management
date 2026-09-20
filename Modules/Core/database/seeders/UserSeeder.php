<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Entities\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء الحساب الإداري (Admin)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'first_name_ar' => 'المدير',
                'last_name_ar' => 'العام',
                'gender' => 'male',
                'password' => Hash::make('password'), // أو 'password' فقط إذا كان الموديل بيعمل Hash تلقائي
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->assignRoleTo($adminUser, 'admin');

        // 2. حساب تجريبي لأستاذ (User + Teacher Profile)
        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'first_name' => 'Ahmad',
                'last_name' => 'Hassan',
                'gender' => 'male',
                'password' => Hash::make('password'),
                'user_type' => 'teacher',
                'is_active' => true,
            ]
        );
        $this->assignRoleTo($teacherUser, 'teacher');

        // 3. حساب تجريبي لطالب (User + Student Profile)
        $studentUser = User::firstOrCreate(
            ['email' => 'student@school.com'],
            [
                'first_name' => 'Sami',
                'last_name' => 'Ali',
                'gender' => 'male',
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'is_active' => true,
            ]
        );
        $this->assignRoleTo($studentUser, 'student');
    }

    private function assignRoleTo($user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$user->hasRole($roleName)) {
            $user->roles()->attach($role->id);
        }
    }
}
