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
        Schema::create('message_statistics', function (Blueprint $table) {
            $table->id();$table->foreignId('message_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedInteger('read_count')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedInteger('forward_count')->default(0);
            $table->timestamp('first_read_at')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_statistics');
    }
};
