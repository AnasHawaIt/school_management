<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\Holiday;
use Modules\School\Entities\AcademicYear;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = AcademicYear::where('is_current', true)->first();

        $holidays = [
            [
                'academic_year_id' => $currentYear?->id,
                'name' => 'Summer Vacation',
                'start_date' => '2026-06-15',
                'end_date' => '2026-08-31',
                'type' => 'academic',
                'is_recurring' => false,
                'description' => 'End of year summer break',
            ],
            [
                'academic_year_id' => null, // null لأنها تتكرر كل سنة بنفس التاريخ
                'name' => 'New Year',
                'start_date' => '2026-01-01',
                'end_date' => '2026-01-01',
                'type' => 'public',
                'is_recurring' => true,
                'description' => 'Happy New Year',
            ],
            [
                'academic_year_id' => $currentYear?->id,
                'name' => 'Mid-Term Break',
                'start_date' => '2026-01-15',
                'end_date' => '2026-01-30',
                'type' => 'academic',
                'is_recurring' => false,
                'description' => 'Break between first and second semesters',
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['name' => $holiday['name'], 'start_date' => $holiday['start_date']],
                $holiday
            );
        }

        $this->command->info('School Holidays seeded successfully!');
    }
}
