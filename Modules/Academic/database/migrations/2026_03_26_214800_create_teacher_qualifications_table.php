<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('type', ['degree', 'certificate', 'training', 'award']); // نوع المؤهل
            $table->string('title'); // عنوان المؤهل
            $table->string('institution'); // الجهة المانحة
            $table->string('field_of_study')->nullable(); // مجال الدراسة
            $table->year('year_obtained'); // سنة الحصول
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء (للشهادات)
            $table->string('document')->nullable(); // مسار الوثيقة
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_qualifications');
    }
};
