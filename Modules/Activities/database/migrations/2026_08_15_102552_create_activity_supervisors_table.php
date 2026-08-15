<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_supervisors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->restrictOnDelete();

            $table->string('role', 50)->nullable();

            $table->boolean('is_primary')
                ->default(false);

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique([
                'activity_id',
                'teacher_id',
            ]);

            $table->index([
                'activity_id',
                'is_primary',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_supervisors');
    }
};
