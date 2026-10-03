<?php

namespace Modules\Attendance\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Attendance\Entities\AttendanceStatus;

class AttendanceStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Present',  'name_ar' => 'حاضر',       'code' => 'P', 'color' => '#10B981', 'is_present' => true],
            ['name' => 'Absent',   'name_ar' => 'غائب',       'code' => 'A', 'color' => '#EF4444', 'is_present' => false],
            ['name' => 'Late',     'name_ar' => 'متأخر',      'code' => 'L', 'color' => '#F59E0B', 'is_present' => true],
            ['name' => 'Excused',  'name_ar' => 'غياب بعذر',  'code' => 'E', 'color' => '#3B82F6', 'is_present' => false],
        ];

        foreach ($statuses as $status) {
            AttendanceStatus::firstOrCreate(['code' => $status['code']], $status);
            $this->command->info("✅ Status: {$status['name']} ({$status['code']})");
        }
    }

}
