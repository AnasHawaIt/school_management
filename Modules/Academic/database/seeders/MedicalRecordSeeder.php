<?php

namespace Modules\Academic\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        // جلب أول 5 طلاب موجودين في النظام
        $students = DB::table('students')->limit(5)->get();

        if ($students->isEmpty()) {
            $this->command->warn('No students found! Please run StudentSeeder first.');
            return;
        }

        foreach ($students as $student) {
            DB::table('student_medical_records')->updateOrInsert(
                ['student_id' => $student->id],
                [
                    'chronic_diseases' => 'None',
                    'allergies' => 'Peanuts, Dust',
                    'medications' => null,
                    'disabilities' => null,
                    'special_needs' => 'Needs to sit in the front row due to vision issues',
                    'doctor_name' => 'Dr. Ahmad Ali',
                    'doctor_phone' => '0933123456',
                    'insurance_number' => 'INS-' . rand(1000, 9999),
                    'insurance_company' => 'Syrian Insurance Co.',
                    'notes' => 'Student is overall healthy, but keep an eye during PE classes.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Student medical records seeded successfully!');
    }
}
