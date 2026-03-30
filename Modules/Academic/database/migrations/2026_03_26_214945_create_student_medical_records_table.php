<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('chronic_diseases')->nullable();   // أمراض مزمنة
            $table->text('allergies')->nullable();           // حساسية
            $table->text('medications')->nullable();         // أدوية دائمة
            $table->text('disabilities')->nullable();        // إعاقات
            $table->text('special_needs')->nullable();       // احتياجات خاصة
            $table->string('doctor_name')->nullable();
            $table->string('doctor_phone')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_company')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_medical_records');
    }
};
