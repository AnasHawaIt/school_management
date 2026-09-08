<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title', 255);

            $table->longText('body');

            $table->string('audience', 30)
                ->default('all');

            $table->string('status', 30)
                ->default('draft');

            $table->string('priority', 20)
                ->default('normal');

            $table->boolean('is_pinned')
                ->default(false);

            $table->timestamp('scheduled_at')
                ->nullable();

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index('audience');
            $table->index('status');
            $table->index('priority');
            $table->index('scheduled_at');
            $table->index('published_at');
            $table->index('expires_at');

            $table->index([
                'status',
                'published_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
