<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Monthly, Midterm, Final
            $table->string('name_ar');        // شهري، نصفي، نهائي
            $table->integer('weight');        // الوزن من الدرجة الكاملة (مثلاً 20, 30, 50)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_types');
    }
};
