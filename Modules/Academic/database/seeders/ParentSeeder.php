<?php

namespace Modules\Academic\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء مستخدم لولي الأمر
        $parentUser = User::firstOrCreate(
            ['email' => 'parent.test@school.com'],
            [
                'first_name' => 'Mohammad',
                'last_name' => 'Al-Homsi',
                'password' => bcrypt('password'),
                'user_type' => 'parent',
                'is_active' => true,
            ]
        );

        // 2. إنشاء سجل ولي الأمر
        $parentId = DB::table('parents')->insertGetId([
            'user_id' => $parentUser->id,
            'national_id' => '010100' . rand(1000, 9999),
            'occupation' => 'Software Engineer',
            'education_level' => 'bachelor',
            'city' => 'Damascus',
            'created_at' => now(),
        ]);

        // 3. ربط ولي الأمر بأول طالبين في النظام (كأخوة)
        $studentIds = DB::table('students')->limit(2)->pluck('id');

        foreach ($studentIds as $index => $studentId) {
            DB::table('student_parent')->updateOrInsert(
                ['student_id' => $studentId, 'parent_id' => $parentId],
                [
                    'relationship' => $index == 0 ? 'father' : 'guardian',
                    'is_primary_contact' => $index == 0,
                    'can_pickup' => true,
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info('Parent and family links seeded successfully!');
    }
}
