<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_id')->unique(); // رقم الموظف
            $table->string('first_name');
            $table->string('last_name');
            $table->string('first_name_ar')->nullable();
            $table->string('last_name_ar')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->string('national_id')->nullable()->unique(); // رقم الهوية
            $table->string('nationality')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('photo')->nullable();
            $table->string('specialization'); // التخصص
            $table->integer('experience_years')->default(0);
            $table->date('joining_date');
            $table->decimal('salary', 10, 2)->default(0);
            $table->enum('contract_type', ['full_time', 'part_time', 'temporary'])->default('full_time');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'resigned'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
