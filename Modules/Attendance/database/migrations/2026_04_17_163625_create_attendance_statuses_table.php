<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // present, absent, late, excused
            $table->string('name_ar');         // حاضر، غائب، متأخر، بعذر
            $table->string('code')->unique();  // P, A, L, E
            $table->string('color');           // للواجهة
            $table->boolean('is_present')->default(false); // هل يُحسب حضوراً؟
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_statuses');
    }
};
