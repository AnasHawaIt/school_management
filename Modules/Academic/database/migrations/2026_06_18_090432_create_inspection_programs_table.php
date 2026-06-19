<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->date('inspection_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('type', ['scheduled', 'surprise'])->default('scheduled');
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->text('objectives')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['section_id', 'inspection_date']);
        });
        Schema::create('inspection_program_counselor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_program_id')->constrained('inspection_programs')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('counselors')->onDelete('cascade');
            $table->enum('role', ['lead', 'member'])->default('member');
            $table->text('objectives')->nullable();
            $table->enum('result', ['excellent', 'good', 'average', 'weak'])->nullable();
            $table->timestamps();
            $table->unique(['inspection_program_id', 'counselor_id'], 'program_counselor_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_programs');
    }
};
