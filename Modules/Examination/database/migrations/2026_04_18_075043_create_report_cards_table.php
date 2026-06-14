<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->decimal('total_marks', 8, 2)->default(0);    // مجموع الدرجات
            $table->decimal('obtained_marks', 8, 2)->default(0); // الدرجات المحصّلة
            $table->decimal('percentage', 5, 2)->default(0);     // النسبة المئوية
            $table->string('grade')->nullable();                  // A, B, C, D, F
            $table->integer('rank')->nullable();                  // الترتيب على الشعبة
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->text('teacher_remarks')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'semester_id'], 'student_semester_unique');
            $table->index(['section_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
