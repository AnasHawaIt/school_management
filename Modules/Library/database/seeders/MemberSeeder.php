<?php


namespace Modules\Library\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User;
use Modules\Library\Entities\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::create([
            'first_name' => 'محمد',
            'last_name' => 'أحمد',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'first_name' => 'أحمد',
            'last_name' => 'علي',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
        ]);

        Member::create([
            'user_id' => $user1->id,
            'start_date' => now()->toDateString(),
            'status' => 'active',
            'membership_number' => 'LIB-0001',
            'end_date' => null,
        ]);

        Member::create([
            'user_id' => $user2->id,
            'start_date' => now()->toDateString(),
            'status' => 'active',
            'membership_number' => 'LIB-0002',
            'end_date' => null,
        ]);
    }
}
