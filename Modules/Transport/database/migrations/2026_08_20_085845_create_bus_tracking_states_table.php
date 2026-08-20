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
        Schema::create('bus_tracking_states', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bus_id')
                ->constrained('buses')
                ->cascadeOnDelete();

            $table->foreignId('route_stop_id')
                ->constrained('route_stops')
                ->cascadeOnDelete();

            $table->enum('stage', [
                'upcoming',
                'approaching',
                'near',
                'arriving',
                'arrived',
            ])->default('upcoming');

            $table->timestamp('last_notified_at')->nullable();

            $table->timestamps();

            $table->unique([
                'bus_id',
                'route_stop_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_tracking_states');
    }
};
