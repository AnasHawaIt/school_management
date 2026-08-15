<?php

namespace Modules\Attendance\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\Teacher;
use Modules\Attendance\Entities\AttendanceStatus;
use Modules\Attendance\Entities\TeacherAttendance;
use Modules\Core\Entities\User;

class TeacherAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::with('user')->get();
        $admin    = User::where('user_type', 'admin')->first();
        $statuses = AttendanceStatus::all()->keyBy('code');

        if ($teachers->isEmpty() || !$admin) {
            $this->command->warn('⚠️  No teachers or admin found.');
            return;
        }

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            if (!in_array($date->dayOfWeek, [5, 6])) {
                $dates->push($date->format('Y-m-d'));
            }
        }

        foreach ($dates as $date) {
            foreach ($teachers as $teacher) {
                $rand = rand(1, 100);
                $code = match(true) {
                    $rand <= 90 => 'P',
                    $rand <= 97 => 'L',
                    default     => 'A',
                };

                TeacherAttendance::firstOrCreate(
                    ['teacher_id' => $teacher->id, 'date' => $date],
                    [
                        'status_id'      => $statuses[$code]->id,
                        'check_in_time'  => $code !== 'A' ? '07:00' : null,
                        'check_out_time' => $code !== 'A' ? '14:00' : null,
                        'late_minutes'   => $code === 'L' ? rand(5, 20) : 0,
                        'recorded_by'    => $admin->id,
                    ]
                );
            }
            $this->command->info("✅ Teacher attendance recorded for: {$date}");
        }
    }
}
