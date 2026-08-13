<?php

namespace Modules\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\Counselor;
use Modules\Academic\Entities\PointCategory;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\StudentPoint;
use Modules\Academic\Entities\Teacher;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Semester;

class SampleStudentPointsSeeder extends Seeder
{
    public function run(): void
    {
        $students   = Student::where('status', 'active')->with('user')->take(8)->get();
        $year       = AcademicYear::where('is_current', true)->first();
        $semester   = Semester::where('is_current', true)->first();
        $counselor  = Counselor::first();
        $teacher    = Teacher::first();
        $categories = PointCategory::all()->keyBy('name');

        if ($students->isEmpty() || !$year || !$semester) {
            $this->command->warn('⚠️  Missing required data.');
            return;
        }

        foreach ($students as $student) {
            // نقاط إيجابية — حضور (من الموجه)
            if ($categories->has('Attendance') && $counselor) {
                StudentPoint::firstOrCreate(
                    ['student_id' => $student->id, 'point_category_id' => $categories['Attendance']->id, 'date' => now()->subDays(1)->format('Y-m-d')],
                    [
                        'academic_year_id' => $year->id,
                        'semester_id'      => $semester->id,
                        'type'             => 'positive',
                        'points'           => 2,
                        'reason'           => 'حضور منتظم',
                        'given_by_type'    => 'counselor',
                        'given_by_id'      => $counselor->id,
                    ]
                );
            }

            // نقاط إيجابية — مشاركة صفية (من المعلم)
            if ($categories->has('Participation') && $teacher) {
                StudentPoint::firstOrCreate(
                    ['student_id' => $student->id, 'point_category_id' => $categories['Participation']->id, 'date' => now()->subDays(2)->format('Y-m-d')],
                    [
                        'academic_year_id' => $year->id,
                        'semester_id'      => $semester->id,
                        'type'             => 'positive',
                        'points'           => 3,
                        'reason'           => 'مشاركة فعّالة في حصة الرياضيات',
                        'given_by_type'    => 'teacher',
                        'given_by_id'      => $teacher->id,
                    ]
                );
            }

            // نقاط سلبية — بعض الطلاب فقط (30%)
            if (rand(1, 100) <= 30 && $categories->has('Late') && $counselor) {
                StudentPoint::firstOrCreate(
                    ['student_id' => $student->id, 'point_category_id' => $categories['Late']->id, 'date' => now()->subDays(3)->format('Y-m-d')],
                    [
                        'academic_year_id' => $year->id,
                        'semester_id'      => $semester->id,
                        'type'             => 'negative',
                        'points'           => 2,
                        'reason'           => 'تأخر عن الحضور الصباحي',
                        'given_by_type'    => 'counselor',
                        'given_by_id'      => $counselor->id,
                    ]
                );
            }

            // نقاط سلبية — عدم النظافة (20%)
            if (rand(1, 100) <= 20 && $categories->has('Cleanliness Violation') && $counselor) {
                StudentPoint::firstOrCreate(
                    ['student_id' => $student->id, 'point_category_id' => $categories['Cleanliness Violation']->id, 'date' => now()->subDays(4)->format('Y-m-d')],
                    [
                        'academic_year_id' => $year->id,
                        'semester_id'      => $semester->id,
                        'type'             => 'negative',
                        'points'           => 3,
                        'reason'           => 'عدم الالتزام بنظافة الفصل',
                        'given_by_type'    => 'counselor',
                        'given_by_id'      => $counselor->id,
                    ]
                );
            }

            $name = $student->user->first_name . ' ' . $student->user->last_name;
            $this->command->info("✅ نقاط أُضيفت للطالب: {$name}");
        }
    }
}
