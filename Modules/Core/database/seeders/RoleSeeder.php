<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Role;

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
                'description' => 'Teacher access to manage classes and students',
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Student access to view grades and attendance',
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Parent access to view children information',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}
