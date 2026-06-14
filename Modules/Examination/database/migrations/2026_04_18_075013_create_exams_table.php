<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->foreignId('exam_type_id')->constrained('exam_types')->onDelete('restrict');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('restrict');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->decimal('total_marks', 6, 2);   // الدرجة الكاملة
            $table->decimal('pass_marks', 6, 2);    // درجة النجاح
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['section_id', 'semester_id']);
            $table->index(['subject_id', 'exam_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
