<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Teachers (Academic Module)
            ['name' => 'teachers.view', 'display_name' => 'View Teachers', 'description' => 'Can view teachers list'],
            ['name' => 'teachers.create', 'display_name' => 'Create Teacher', 'description' => 'Can register new teachers'],
            ['name' => 'teachers.update', 'display_name' => 'Update Teacher', 'description' => 'Can update teacher profiles'],
            ['name' => 'teachers.delete', 'display_name' => 'Delete Teacher', 'description' => 'Can remove teachers'],

            // Students (Academic Module)
            ['name' => 'students.view', 'display_name' => 'View Students', 'description' => 'Can view students list'],
            ['name' => 'students.create', 'display_name' => 'Create Student', 'description' => 'Can enroll new students'],
            ['name' => 'students.update', 'display_name' => 'Update Student', 'description' => 'Can update student profiles'],

            // Sections & Classes
            ['name' => 'sections.manage', 'display_name' => 'Manage Sections', 'description' => 'Can create and assign sections'],
            ['name' => 'grades.manage', 'display_name' => 'Manage Grades', 'description' => 'Can manage school grades/levels'],

            // Subjects & Timetables
            ['name' => 'subjects.manage', 'display_name' => 'Manage Subjects', 'description' => 'Can manage school subjects'],
            ['name' => 'timetables.view', 'display_name' => 'View Timetables', 'description' => 'Can view class schedules'],
            ['name' => 'timetables.manage', 'display_name' => 'Manage Timetables', 'description' => 'Can create and edit schedules'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all()->pluck('id');
            $adminRole->permissions()->sync($allPermissions);
            $this->command->info('Admin role assigned all permissions!');
        }
        $teacherRole = Role::where('name', 'teacher')->first();
        if ($teacherRole) {
            $teacherPerms = Permission::whereIn('name', [
                'students.view',
                'timetables.view',
                'teachers.view'
            ])->pluck('id');
            $teacherRole->permissions()->syncWithoutDetaching($teacherPerms);
        }
    }
}
