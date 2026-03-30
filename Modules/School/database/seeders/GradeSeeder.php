<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            // Primary Level
            ['name' => 'Grade 1', 'level' => 'primary', 'order' => 1],
            ['name' => 'Grade 2', 'level' => 'primary', 'order' => 2],
            ['name' => 'Grade 3', 'level' => 'primary', 'order' => 3],
            ['name' => 'Grade 4', 'level' => 'primary', 'order' => 4],
            ['name' => 'Grade 5', 'level' => 'primary', 'order' => 5],
            ['name' => 'Grade 6', 'level' => 'primary', 'order' => 6],

            // Middle Level
            ['name' => 'Grade 7', 'level' => 'middle', 'order' => 7],
            ['name' => 'Grade 8', 'level' => 'middle', 'order' => 8],
            ['name' => 'Grade 9', 'level' => 'middle', 'order' => 9],

            // High Level
            ['name' => 'Grade 10', 'level' => 'high', 'order' => 10],
            ['name' => 'Grade 11', 'level' => 'high', 'order' => 11],
            ['name' => 'Grade 12', 'level' => 'high', 'order' => 12],
        ];

        foreach ($grades as $grade) {
            Grade::firstOrCreate(
                ['name' => $grade['name']],
                $grade
            );
        }

        $this->command->info('Grades seeded successfully!');
    }
}
