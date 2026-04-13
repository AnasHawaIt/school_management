<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\SchoolClass;
use Modules\School\Entities\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        // جلب كافة المجموعات الدراسية (Classes) النشطة
        $classes = SchoolClass::where('is_active', true)->get();

        if ($classes->isEmpty()) {
            $this->command->warn('No Classes found! Please run ClassSeeder first.');
            return;
        }

        foreach ($classes as $class) {
            // لنفترض أن كل Class يحتوي على شعبتين افتراضيتين
            $sections = [
                ['name' => 'A', 'room' => 'R-' . $class->id . '01'],
                ['name' => 'B', 'room' => 'R-' . $class->id . '02'],
            ];

            foreach ($sections as $sectionData) {
                Section::updateOrCreate(
                    [
                        'class_id' => $class->id,
                        'name' => $sectionData['name']
                    ],
                    [
                        'max_students' => $class->max_students ?? 30,
                        'current_students' => 0, // يبدأ بـ 0 حتى يتم انتساب الطلاب
                        'room_number' => $sectionData['room'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Sections (A & B) have been created for all classes!');
    }
}
