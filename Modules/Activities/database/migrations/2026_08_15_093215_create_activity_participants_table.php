<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete();

            $table->string('participant_type', 30);
            $table->unsignedBigInteger('participant_id');

            $table->string('role', 30)
                ->nullable();

            $table->enum('status', [
                'registered',
                'confirmed',
                'attended',
                'absent',
                'cancelled',
            ])->default('registered');

            $table->timestamp('registered_at')
                ->nullable();

            $table->timestamp('confirmed_at')
                ->nullable();

            $table->timestamp('attended_at')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index([
                'participant_type',
                'participant_id',
            ]);

            $table->index([
                'activity_id',
                'status',
            ]);

            $table->unique(
                [
                    'activity_id',
                    'participant_type',
                    'participant_id',
                ],
                'activity_participant_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_participants');
    }
};
