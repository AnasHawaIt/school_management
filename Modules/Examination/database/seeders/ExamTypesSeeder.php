<?php

namespace Modules\Examination\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Examination\Entities\ExamType;

class ExamTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Monthly',  'name_ar' => 'شهري',  'weight' => 20],
            ['name' => 'Midterm',  'name_ar' => 'نصفي',  'weight' => 30],
            ['name' => 'Final',    'name_ar' => 'نهائي', 'weight' => 50],
            ['name' => 'Quiz',     'name_ar' => 'اختبار قصير', 'weight' => 10],
        ];

        foreach ($types as $type) {
            ExamType::firstOrCreate(['name' => $type['name']], $type);
            $this->command->info("✅ Exam type: {$type['name']}");
        }
    }
}
