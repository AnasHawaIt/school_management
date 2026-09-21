<?php

namespace Modules\Academic\database\seeders;

use App\Entities\Counselor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;

class CounselorsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = Section::with('class.grade')->take(6)->get();
        $year     = AcademicYear::where('is_current', true)->first();

        if ($sections->isEmpty() || !$year) {
            $this->command->warn('⚠️  No sections or academic year found. Run School seeder first.');
            return;
        }

        $counselors = [
            [
                'first_name'     => 'Nadia',
                'last_name'      => 'Mansour',
                'first_name_ar'  => 'نادية',
                'last_name_ar'   => 'منصور',
                'gender'         => 'female',
                'email'          => 'nadia.counselor@school.com',
                'specialization' => 'Educational Psychology',
                'sections'       => [0, 1],
            ],
            [
                'first_name'     => 'Karim',
                'last_name'      => 'Haddad',
                'first_name_ar'  => 'كريم',
                'last_name_ar'   => 'حداد',
                'gender'         => 'male',
                'email'          => 'karim.counselor@school.com',
                'specialization' => 'Behavioral Guidance',
                'sections'       => [2, 3],
            ],
            [
                'first_name'     => 'Rima',
                'last_name'      => 'Khalil',
                'first_name_ar'  => 'ريما',
                'last_name_ar'   => 'خليل',
                'gender'         => 'female',
                'email'          => 'rima.counselor@school.com',
                'specialization' => 'Academic Guidance',
                'sections'       => [4, 5],
            ],
        ];

        foreach ($counselors as $index => $data) {

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'gender'     => $data['gender'],
                    'password'   => Hash::make('Counselor@123'),
                    'user_type'  => 'counselor',
                    'is_active'  => true,
                ]
            );


            $counselor = Counselor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'counselor_id'   => 'CNS-' . now()->year . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'specialization' => $data['specialization'],
                    'status'         => 'active',
                ]
            );


            foreach ($data['sections'] as $sectionIndex) {
                if ($sections->has($sectionIndex)) {
                    $section = $sections->get($sectionIndex);
                    $counselor->sections()->syncWithoutDetaching([
                        $section->id => ['academic_year_id' => $year->id],
                    ]);

                    $this->command->info(
                        "✅ {$counselor->counselor_id} — {$data['first_name']} {$data['last_name']}" .
                        " → مسؤول عن شعبة [{$section->name}]"
                    );
                }
            }
        }
    }
}
