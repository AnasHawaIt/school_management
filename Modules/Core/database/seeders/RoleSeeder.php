<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\app\Entities\Permission;
use Modules\Core\app\Entities\Role;

// تأكد من وجود موديل الصلاحيات

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and control',

            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Manage classes, subjects, and student marks',

            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'View schedule, attendance, and grades',

            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Monitor children performance and attendance',

            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Roles seeded successfully!');

        $this->seedDefaultPermissions();
    }

    private function seedDefaultPermissions()
    {

        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_teachers',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions); // إذا كنت تستخدم Spatie
        }
    }
}
