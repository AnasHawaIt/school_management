<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->integer('period_number'); // رقم الحصة
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('room_number')->nullable(); // رقم الغرفة
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // منع التعارض في نفس الشعبة بنفس اليوم والحصة
            $table->unique(['section_id', 'day_of_week', 'period_number', 'semester_id'], 'section_day_period_unique');
            // منع تعارض المعلم
            $table->unique(['teacher_id', 'day_of_week', 'period_number', 'semester_id'], 'teacher_day_period_unique');
//منع تعارض القاعة
            $table->unique(['room_number', 'day_of_week', 'period_number', 'semester_id'], 'room_day_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
