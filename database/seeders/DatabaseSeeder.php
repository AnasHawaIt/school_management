<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Core\app\Entities\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            //'name' => 'Test User',

            'first_name' => 'Test',
            'last_name'  => 'User',
            'email' => 'test@example.com',
        ]);
    }
}

