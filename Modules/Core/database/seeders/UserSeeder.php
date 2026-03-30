<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User;
use Modules\Core\Entities\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command->info('Admin user created: admin@school.com / password');

        // Create Teacher User
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
                'phone' => '1234567891',
                'user_type' => 'teacher',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $teacherRole = Role::where('name', 'teacher')->first();
        if ($teacherRole && !$teacher->hasRole('teacher')) {
            $teacher->assignRole('teacher');
        }

        $this->command->info('Teacher user created: teacher@school.com / password');

        // Create Student User
        $student = User::firstOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'phone' => '1234567892',
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole && !$student->hasRole('student')) {
            $student->assignRole('student');
        }

        $this->command->info('Student user created: student@school.com / password');

        // Create Parent User
        $parent = User::firstOrCreate(
            ['email' => 'parent@school.com'],
            [
                'name' => 'Parent User',
                'password' => Hash::make('password'),
                'phone' => '1234567893',
                'user_type' => 'parent',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $parentRole = Role::where('name', 'parent')->first();
        if ($parentRole && !$parent->hasRole('parent')) {
            $parent->assignRole('parent');
        }

        $this->command->info('Parent user created: parent@school.com / password');
    }
}
