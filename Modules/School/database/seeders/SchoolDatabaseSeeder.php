<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AcademicYearSeeder::class,
            GradeSeeder::class,
        ]);

        $this->command->info('School module seeded successfully!');
    }
}
