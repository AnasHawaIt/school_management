<?php

namespace Modules\Academic\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Entities\Student;
use Modules\Academic\Entities\StudentMedicalRecord;
use Modules\Core\app\Entities\User;
use Modules\School\Entities\AcademicYear;
use Modules\School\Entities\Section;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {// جلب كل الشعب المتاحة في السنة الحالية
        $academicYear = AcademicYear::where('is_current', true)->first();

        $sections = Section::whereHas('class', function($q) use ($academicYear) {
            $q->where('academic_year_id', $academicYear->id);
        })->get();

        if ($sections->isEmpty()) {
            $this->command->error('❌ No sections found for the current year!');
            return;
        }

        if (!$sections || !$academicYear) {
            $this->command->warn('⚠️  No section or academic year found. Run School module seeder first.');
            return;
        }

        $students = [
            ['first_name' => 'Mohammed', 'last_name' => 'Ahmed',   'first_name_ar' => 'محمد',    'last_name_ar' => 'أحمد',   'gender' => 'male',   'dob' => '2012-03-15'],
            ['first_name' => 'Fatima',   'last_name' => 'Hassan',  'first_name_ar' => 'فاطمة',   'last_name_ar' => 'حسن',    'gender' => 'female', 'dob' => '2012-07-22'],
            ['first_name' => 'Khalid',   'last_name' => 'Omar',    'first_name_ar' => 'خالد',    'last_name_ar' => 'عمر',    'gender' => 'male',   'dob' => '2011-11-08'],
            ['first_name' => 'Noor',     'last_name' => 'Ali',     'first_name_ar' => 'نور',     'last_name_ar' => 'علي',    'gender' => 'female', 'dob' => '2012-01-30'],
            ['first_name' => 'Abdullah', 'last_name' => 'Salem',   'first_name_ar' => 'عبدالله', 'last_name_ar' => 'سالم',   'gender' => 'male',   'dob' => '2011-09-14'],
            ['first_name' => 'Mariam',   'last_name' => 'Youssef', 'first_name_ar' => 'مريم',    'last_name_ar' => 'يوسف',   'gender' => 'female', 'dob' => '2012-05-03'],
            ['first_name' => 'Hassan',   'last_name' => 'Nasser',  'first_name_ar' => 'حسن',     'last_name_ar' => 'ناصر',   'gender' => 'male',   'dob' => '2011-12-19'],
            ['first_name' => 'Aisha',    'last_name' => 'Mahmoud', 'first_name_ar' => 'عائشة',   'last_name_ar' => 'محمود',  'gender' => 'female', 'dob' => '2012-08-25'],
        ];

        foreach ($students as $index => $data) {
            $randomSection = $sections->random();
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => strtolower($data['first_name'] . '.' . $data['last_name']) . '@school.com',
                'password'   => Hash::make('password'),
                'user_type'  => 'student',
            ]);

            $student = Student::create([
                'user_id'            => $user->id,
                'student_id'         => 'STU-' . now()->year . '-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                // تم حذف first_name و last_name من هنا لأنها غير موجودة بجدول students
                'enrollment_date'    => now()->startOfYear()->format('Y-m-d'),
                'current_section_id' => $randomSection->id,
                'academic_year_id'   => $academicYear->id,
                'blood_type'         => collect(['A+', 'B+', 'O+', 'AB+'])->random(),
                'nationality'        => 'Saudi',
                'status'             => 'active',
             ]);
            // سجل طبي فارغ لكل طالب
            StudentMedicalRecord::create(['student_id' => $student->id]);

            $user->assignRole('student');

            $this->command->info("✅ Student created: {$student->full_name} [{$student->student_id}]");
        }

        // تحديث عدد الطلاب في الشعبة
        $randomSection->increment('current_students');
        $this->command->info("✅ Section [{$randomSection->name}] updated: {$randomSection->current_students} students");
    }
}
