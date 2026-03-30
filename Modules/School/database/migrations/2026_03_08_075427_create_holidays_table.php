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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name'); // e.g., "Summer Vacation", "Eid Holiday"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['public', 'academic', 'religious', 'other'])->default('academic');
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false); // يتكرر كل سنة
            $table->timestamps();

            $table->index('academic_year_id');
            $table->index('start_date');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
