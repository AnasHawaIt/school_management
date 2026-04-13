<?php

namespace Modules\Academic\Database\Seeders;


use Illuminate\Database\Seeder;


class AcademicDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
         //   SubjectsSeeder::class,      // ينشئ المواد لكل صف

            // 2. المدرسون (أساس لجدول المؤهلات)
         //   TeachersSeeder::class,      // ينشئ حسابات المدرسين
          //  QualificationSeeder::class, // يضيف الشهادات للمدرسين المنشأين

            // 3. الطلاب (أساس لسجلات الصحة وأولياء الأمور)
            StudentsSeeder::class,      // ينشئ الطلاب ويوزعهم على الشعب
            MedicalRecordSeeder::class, // يضيف السجلات الطبية للطلاب المنشأين

            // 4. أولياء الأمور (تعتمد على وجود الطلاب للربط)
            GuardiansSeeder::class,     // ينشئ أولياء أمور عشوائيين ويربطهم
          //  ParentSeeder::class,
        ]);
    }
}
