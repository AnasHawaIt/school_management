<?php

namespace Modules\Examination\database\seeders;

use App\Entities\Subject;
use App\Entities\Teacher;
use Illuminate\Database\Seeder;
use Modules\Core\app\Entities\User;
use Modules\Examination\Entities\Exam;
use Modules\Examination\Entities\ExamType;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;
use Modules\School\Entities\Semester;

class ExamsSeeder extends Seeder
{
    public function run(): void
    {
        $section     = Section::first();
        $year        = AcademicYear::where('is_current', true)->first();
        $semester    = Semester::where('is_current', true)->first();
        $teacher     = Teacher::first();
        $midtermType = ExamType::where('name', 'Midterm')->first();
        $finalType   = ExamType::where('name', 'Final')->first();
        $admin       = User::where('user_type', 'admin')->first();

        if (!$section || !$year || !$semester || !$teacher || !$midtermType) {
            $this->command->warn('⚠️  Missing required data. Run School & Academic seeders first.');
            return;
        }

        $subjects = Subject::where('status', 'active')->take(3)->get();

        foreach ($subjects as $index => $subject) {
            // امتحان نصفي
            $exam = Exam::firstOrCreate(
                [
                    'subject_id' => $subject->id,
                    'section_id' => $section->id,
                    'exam_type_id' => $midtermType->id,
                    'semester_id'  => $semester->id,
                ],
                [
                    'name'             => "Midterm - {$subject->name}",
                    'name_ar'          => "نصفي - {$subject->name_ar}",
                    'academic_year_id' => $year->id,
                    'teacher_id'       => $teacher->id,
                    'exam_date'        => now()->subDays(10 + $index)->format('Y-m-d'),
                    'start_time'       => '09:00',
                    'end_time'         => '11:00',
                    'room'             => 'Room 10' . $index,
                    'total_marks'      => 50,
                    'pass_marks'       => 25,
                    'status'           => 'completed',
                ]
            );

            $this->command->info("✅ Exam created: {$exam->name}");
        }
    }
}
