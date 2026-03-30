<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('first_name_ar')->nullable();
            $table->string('last_name_ar')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('national_id')->nullable()->unique();
            $table->string('nationality')->nullable();
            $table->string('phone');
            $table->string('phone_secondary')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('occupation')->nullable();   // المهنة
            $table->string('employer')->nullable();     // جهة العمل
            $table->string('work_phone')->nullable();
            $table->enum('education_level', ['none', 'primary', 'secondary', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('student_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->enum('relationship', ['father', 'mother', 'guardian', 'other']); // صلة القرابة
            $table->boolean('is_primary_contact')->default(false); // جهة الاتصال الأساسية
            $table->boolean('can_pickup')->default(true); // صلاحية استلام الطالب
            $table->timestamps();

            $table->unique(['student_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parent');
        Schema::dropIfExists('parents');
    }
};
