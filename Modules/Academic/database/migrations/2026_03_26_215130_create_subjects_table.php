<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // رمز المادة
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade'); // المرحلة
            $table->integer('weekly_hours')->default(1); // ساعات أسبوعية
            $table->integer('credit_hours')->default(1); // ساعات معتمدة
            $table->decimal('pass_mark', 5, 2)->default(50.00); // علامة النجاح
            $table->decimal('full_mark', 5, 2)->default(100.00); // العلامة الكاملة
            $table->boolean('is_mandatory')->default(true); // إلزامي أم اختياري
            $table->string('color')->nullable(); // لون المادة في الجدول
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });

        // ربط المعلم بالمادة والشعبة والسنة
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
//هنا عند اضافة الشرط لايمكن لاي مادة ان تحمل اكثر من استاذ
        //    $table->unique(['subject_id', 'section_id', 'academic_year_id','semester_id'], 'subject_section_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('subjects');
    }
};
