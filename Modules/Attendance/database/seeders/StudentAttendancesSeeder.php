<?php

namespace Modules\Attendance\database\seeders;

use App\Entities\Student;
use Illuminate\Database\Seeder;
use Modules\Attendance\app\Entities\AttendanceStatus;
use Modules\Attendance\app\Entities\StudentAttendance;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;
use Modules\School\Entities\Semester;

class StudentAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students     = Student::with('user')->get();
        $academicYear = AcademicYear::where('is_current', true)->first();
        $semester     = Semester::where('is_current', true)->first();
        $section      = Section::first();
        $admin        = User::where('user_type', 'admin')->first();
        $statuses     = AttendanceStatus::all()->keyBy('code');

        if ($students->isEmpty() || !$academicYear || !$semester || !$section || !$admin) {
            $this->command->warn('⚠️  Missing required data. Run School & Academic seeders first.');
            return;
        }

        // نسجّل حضور آخر 7 أيام
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            // تخطي الجمعة والسبت
            if (!in_array($date->dayOfWeek, [5, 6])) {
                $dates->push($date->format('Y-m-d'));
            }
        }

        foreach ($dates as $date) {
            foreach ($students as $student) {
                // توزيع عشوائي واقعي: 85% حضور، 10% غياب، 5% تأخير
                $rand = rand(1, 100);
                $code = match(true) {
                    $rand <= 85 => 'P',
                    $rand <= 95 => 'A',
                    default     => 'L',
                };

                StudentAttendance::firstOrCreate(
                    ['student_id' => $student->id, 'date' => $date],
                    [
                        'section_id'       => $section->id,
                        'academic_year_id' => $academicYear->id,
                        'semester_id'      => $semester->id,
                        'status_id'        => $statuses[$code]->id,
                        'check_in_time'    => $code === 'L' ? '07:30' : ($code === 'P' ? '07:00' : null),
                        'late_minutes'     => $code === 'L' ? rand(5, 30) : 0,
                        'recorded_by'      => $admin->id,
                    ]
                );
            }
            $this->command->info("✅ Student attendance recorded for: {$date}");
        }
    }
}
