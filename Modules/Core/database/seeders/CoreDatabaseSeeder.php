<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;

class CoreDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
        ]);

        $this->command->info('Core module seeded successfully!');
    }
}
