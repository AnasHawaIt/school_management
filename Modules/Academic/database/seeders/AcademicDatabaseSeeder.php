<?php

namespace Modules\Academic\Database\Seeders;


use Illuminate\Database\Seeder;


class AcademicDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
           SubjectsSeeder::class,
           TeachersSeeder::class,
           QualificationSeeder::class,
           StudentsSeeder::class,
            MedicalRecordSeeder::class,
            GuardiansSeeder::class,
            ParentSeeder::class,
            CounselorsSeeder::class,
            InspectionProgramsSeeder::class,
            PointCategoriesSeeder::class,
            SampleStudentPointsSeeder::class,
        ]);
    }
}
