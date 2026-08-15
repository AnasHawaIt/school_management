<?php

namespace Modules\Academic\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Academic\Entities\Counselor;
use Modules\Academic\Entities\InspectionProgram;
use Modules\Core\Entities\User;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;
use Modules\School\Entities\Semester;

class InspectionProgramsSeeder extends Seeder
{
    public function run(): void
    {
        $counselors = Counselor::with('user')->get();
        $sections   = Section::take(4)->get();
        $year       = AcademicYear::where('is_current', true)->first();
        $semester   = Semester::where('is_current', true)->first();
        $admin      = User::where('user_type', 'admin')->first();

        if ($counselors->isEmpty() || $sections->isEmpty() || !$year || !$semester || !$admin) {
            $this->command->warn('⚠️  Missing required data.');
            return;
        }

        $programs = [
            [
                'title'           => 'Monthly Scheduled Inspection — Grade 1',
                'title_ar'        => 'تفقد شهري مجدول — الصف الأول',
                'section_index'   => 0,
                'type'            => 'scheduled',
                'status'          => 'completed',
                'inspection_date' => now()->subDays(15)->format('Y-m-d'),
                'start_time'      => '09:00',
                'end_time'        => '11:00',
                'objectives'      => 'تقييم الأداء التدريسي وانضباط الطلاب ومستوى الفهم العام',
                'counselors'      => [
                    ['index' => 0, 'role' => 'lead',   'observation' => 'الصف منظم والمعلمة متمكنة من المادة. لاحظنا تفاعلاً جيداً من الطلاب.',      'result' => 'good'],
                    ['index' => 1, 'role' => 'member', 'observation' => 'يوصى بتطوير أسلوب التقييم المستمر وتنويع الأنشطة الصفية.',                   'result' => 'good'],
                ],
            ],
            [
                'title'           => 'Surprise Inspection — Grade 2',
                'title_ar'        => 'تفقد مفاجئ — الصف الثاني',
                'section_index'   => 1,
                'type'            => 'surprise',
                'status'          => 'completed',
                'inspection_date' => now()->subDays(8)->format('Y-m-d'),
                'start_time'      => '10:30',
                'end_time'        => '11:30',
                'objectives'      => 'التحقق من تطبيق الخطة الدراسية والتزام المعلمين بالمنهج',
                'counselors'      => [
                    ['index' => 1, 'role' => 'lead',   'observation' => 'الدرس كان مطابقاً للخطة الدراسية. المعلم يستخدم التقنية بشكل فعّال.',        'result' => 'excellent'],
                    ['index' => 2, 'role' => 'member', 'observation' => 'يوصى بتخصيص وقت أكبر للمراجعة في نهاية كل حصة.',                             'result' => 'good'],
                ],
            ],
            [
                'title'           => 'Academic Development Inspection — Grade 3',
                'title_ar'        => 'تفقد تطوير أكاديمي — الصف الثالث',
                'section_index'   => 2,
                'type'            => 'scheduled',
                'status'          => 'pending',
                'inspection_date' => now()->addDays(5)->format('Y-m-d'),
                'start_time'      => '08:00',
                'end_time'        => '10:00',
                'objectives'      => 'متابعة خطط التحسين الأكاديمي بعد نتائج الفصل الأول',
                'counselors'      => [
                    ['index' => 0, 'role' => 'lead',   'observation' => null, 'result' => null],
                    ['index' => 2, 'role' => 'member', 'observation' => null, 'result' => null],
                ],
            ],
            [
                'title'           => 'Behavioral Guidance Inspection — Grade 4',
                'title_ar'        => 'تفقد إرشاد سلوكي — الصف الرابع',
                'section_index'   => 3,
                'type'            => 'scheduled',
                'status'          => 'ongoing',
                'inspection_date' => now()->format('Y-m-d'),
                'start_time'      => '11:00',
                'end_time'        => '13:00',
                'objectives'      => 'تقييم الجو الصفي والعلاقة بين الطلاب والمعلم ورصد أي مشكلات سلوكية',
                'counselors'      => [
                    ['index' => 1, 'role' => 'lead',   'observation' => null, 'result' => null],
                    ['index' => 0, 'role' => 'member', 'observation' => null, 'result' => null],
                ],
            ],
        ];

        foreach ($programs as $programData) {
            $section = $sections->get($programData['section_index']);
            if (!$section) continue;

            $program = InspectionProgram::firstOrCreate(
                [
                    'title'       => $programData['title'],
                    'section_id'  => $section->id,
                    'semester_id' => $semester->id,
                ],
                [
                    'title_ar'        => $programData['title_ar'],
                    'academic_year_id' => $year->id,
                    'inspection_date' => $programData['inspection_date'],
                    'start_time'      => $programData['start_time'],
                    'end_time'        => $programData['end_time'],
                    'type'            => $programData['type'],
                    'status'          => $programData['status'],
                    'objectives'      => $programData['objectives'],
                    'created_by'      => $admin->id,
                ]
            );


            foreach ($programData['counselors'] as $c) {
                $counselor = $counselors->get($c['index']);
                if (!$counselor) continue;

                $program->counselors()->syncWithoutDetaching([
                    $counselor->id => [
                        'role'        => $c['role'],
                        'objectives' => $c['observation'],
                        'result'      => $c['result'],
                    ],
                ]);
            }

            $statusIcon = match($program->status) {
                'completed' => '✅',
                'ongoing'   => '🔄',
                'pending'   => '⏳',
                default     => '❌',
            };

            $this->command->info(
                "{$statusIcon} برنامج تفقد: [{$program->title_ar}]" .
                " — شعبة [{$section->name}]" .
                " — {$program->inspection_date}"
            );
        }
    }
}
