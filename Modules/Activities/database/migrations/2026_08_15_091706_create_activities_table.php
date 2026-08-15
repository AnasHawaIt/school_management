<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('activity_categories')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('title');
            $table->string('title_ar');

            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();

            $table->string('location')->nullable();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->unsignedInteger('capacity')->nullable();

            $table->boolean('registration_required')
                ->default(false);

            $table->dateTime('registration_deadline')->nullable();

            $table->enum('status', [
                'draft',
                'published',
                'ongoing',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->boolean('is_featured')->default(false);

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'start_at']);
            $table->index(['category_id', 'status']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
