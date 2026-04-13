<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('student_id')->unique(); // رقم الطالب
            $table->string('national_id')->nullable()->unique();
            $table->string('nationality')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('photo')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable(); // هاتف الطالب (إن وجد)
            $table->date('enrollment_date'); // تاريخ التسجيل
            $table->foreignId('current_section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'transferred', 'graduated', 'expelled'])->default('active');
            $table->string('previous_school')->nullable(); // المدرسة السابقة
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
