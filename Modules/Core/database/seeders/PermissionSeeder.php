<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [ ['name' => 'library.catalog.view', 'display_name' => 'View Library Catalog', 'description' => 'Can view library catalog and members'],
            ['name' => 'library.catalog.manage', 'display_name' => 'Manage Library Catalog', 'description' => 'Can manage books, copies, authors, categories, publishers, and members'],
            ['name' => 'library.catalog.delete', 'display_name' => 'Delete Library Catalog', 'description' => 'Can soft-delete catalog records'],
            ['name' => 'library.catalog.restore', 'display_name' => 'Restore Library Catalog', 'description' => 'Can restore deleted catalog records'],
            ['name' => 'library.catalog.force_delete', 'display_name' => 'Permanently Delete Library Catalog', 'description' => 'Can permanently delete catalog records'],
            ['name' => 'library.circulation.view', 'display_name' => 'View Library Circulation', 'description' => 'Can view borrowing transactions'],
            ['name' => 'library.circulation.manage', 'display_name' => 'Manage Library Circulation', 'description' => 'Can borrow, return, restore, and delete transactions'],
            ['name' => 'library.circulation.restore', 'display_name' => 'Restore Library Circulation', 'description' => 'Can restore deleted transactions'],
            ['name' => 'library.circulation.force_delete', 'display_name' => 'Permanently Delete Library Circulation', 'description' => 'Can permanently delete transactions'],
            ['name' => 'library.fines.view', 'display_name' => 'View Library Fines', 'description' => 'Can view library fines'],
            ['name' => 'library.fines.manage', 'display_name' => 'Manage Library Fines', 'description' => 'Can settle or waive library fines'],
            ['name' => 'library.books.view', 'display_name' => 'View Books', 'description' => 'Can view books and their details'],
            ['name' => 'library.books.manage', 'display_name' => 'Manage Books', 'description' => 'Can add and update books'],
            ['name' => 'library.books.delete', 'display_name' => 'Delete Books', 'description' => 'Can soft-delete books'],
            ['name' => 'library.books.force_delete', 'display_name' => 'Permanently Delete Books', 'description' => 'Can permanently delete books'],
            ['name' => 'library.copies.view', 'display_name' => 'View Book Copies', 'description' => 'Can view physical book copies'],
            ['name' => 'library.copies.manage', 'display_name' => 'Manage Book Copies', 'description' => 'Can add and update physical book copies'],
            ['name' => 'library.copies.delete', 'display_name' => 'Delete Book Copies', 'description' => 'Can delete physical book copies'],
            ['name' => 'library.transactions.create', 'display_name' => 'Create Borrowings', 'description' => 'Can create borrowing requests'],
            ['name' => 'library.transactions.approve', 'display_name' => 'Approve Borrowings', 'description' => 'Can approve or reject borrowing requests'],
            ['name' => 'announcements.view', 'display_name' => 'View Announcements', 'description' => 'Can view announcements'],
            ['name' => 'announcements.create', 'display_name' => 'Create Announcements', 'description' => 'Can create announcements'],
            ['name' => 'announcements.update', 'display_name' => 'Update Announcements', 'description' => 'Can update announcements'],
            ['name' => 'announcements.delete', 'display_name' => 'Delete Announcements', 'description' => 'Can soft-delete announcements'],
            ['name' => 'announcements.publish', 'display_name' => 'Publish Announcements', 'description' => 'Can publish, schedule, cancel, and expire announcements'],
            ['name' => 'announcements.pin', 'display_name' => 'Pin Announcements', 'description' => 'Can pin and unpin announcements'],
            ['name' => 'announcements.force_delete', 'display_name' => 'Permanently Delete Announcements', 'description' => 'Can permanently delete announcements'],

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
