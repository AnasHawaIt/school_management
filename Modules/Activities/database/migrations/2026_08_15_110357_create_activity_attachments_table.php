<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('original_name');

            $table->string('file_name');

            $table->string('file_path');

            $table->string('disk', 50)
                ->default('public');

            $table->string('mime_type', 100)
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->nullable();

            $table->string('type', 30)
                ->default('other');

            $table->string('title')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index([
                'activity_id',
                'type',
            ]);

            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attachments');
    }
};
