<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('point_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->enum('type', ['positive', 'negative']);
            $table->integer('default_points');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_assign')->default(false);
            $table->timestamps();
        });


        Schema::create('student_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('point_category_id')->constrained('point_categories')->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->enum('type', ['positive', 'negative']);
            $table->integer('points');
            $table->string('reason');
            $table->date('date');


            $table->enum('given_by_type', ['counselor', 'teacher','admin']);
            $table->unsignedBigInteger('given_by_id');


            $table->foreignId('inspection_program_id')->nullable()->constrained('inspection_programs')->onDelete('set null');
            $table->foreignId('student_attendance_id')->nullable()->constrained('student_attendances')->onDelete('set null');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'semester_id']);
            $table->index(['given_by_type', 'given_by_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_points');
        Schema::dropIfExists('point_categories');
    }
};
