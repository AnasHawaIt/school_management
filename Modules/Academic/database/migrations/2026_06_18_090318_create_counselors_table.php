<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('counselor_id')->unique(); // CNS-2026-0001
            $table->string('specialization')->nullable(); // تخصص التوجيه
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // الموجه مسؤول عن عدة شعب
        Schema::create('counselor_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained('counselors')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['counselor_id', 'section_id', 'academic_year_id'], 'counselor_section_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_section');
        Schema::dropIfExists('counselors');
    }
};
