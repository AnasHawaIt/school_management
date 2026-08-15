<?php

namespace Modules\Activities\database\seeders;

use Illuminate\Database\Seeder;

class ActivitiesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ActivityCategorySeeder::class,
        ]);
    }
}
