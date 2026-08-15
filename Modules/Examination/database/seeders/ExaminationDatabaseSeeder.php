<?php

namespace Modules\Examination\database\seeders;

use Illuminate\Database\Seeder;
use Modules\School\Database\Seeders\SectionSeeder;

class ExaminationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $this->call(ExamTypesSeeder::class);
        $this->call(ExamsSeeder::class);
        $this->call(ExamResultsSeeder::class);
    }
}

