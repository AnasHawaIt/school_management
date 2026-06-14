<?php

namespace Modules\Examination\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\Student;
use Modules\Core\Entities\User;
use Modules\Examination\Entities\Exam;
use Modules\Examination\Entities\ExamResult;

class ExamResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exams    = Exam::where('status', 'completed')->get();
        $students = Student::where('status', 'active')->get();
        $admin    = User::where('user_type', 'admin')->first();

        if ($exams->isEmpty() || $students->isEmpty() || !$admin) {
            $this->command->warn('⚠️  No exams or students found.');
            return;
        }

        foreach ($exams as $exam) {
            foreach ($students as $student) {
                $isAbsent = rand(1, 100) <= 5; // 5% غياب

                ExamResult::firstOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $student->id],
                    [
                        'marks_obtained' => $isAbsent ? null : rand((int)$exam->pass_marks - 5, (int)$exam->total_marks),
                        'is_absent'      => $isAbsent,
                        'entered_by'     => $admin->id,
                    ]
                );
            }
            $this->command->info("✅ Results entered for: {$exam->name}");
        }
    }
}
