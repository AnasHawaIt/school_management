<?php

namespace Modules\Academic\Database\Seeders;


use Illuminate\Database\Seeder;

class AcademicDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            TeachersSeeder::class,
            SubjectsSeeder::class,
            StudentsSeeder::class,
            GuardiansSeeder::class,
        ]);
    }
}
