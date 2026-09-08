<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('library_copies')) {
            Schema::create('library_copies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->constrained()->restrictOnDelete();
                $table->string('barcode')->unique();
                $table->enum('status', [
                    'available',
                    'borrowed',
                    'lost',
                    'damaged',
                    'maintenance',
                ])->default('available');
                $table->string('location')->nullable();
                $table->timestamps();
                $table->index(['book_id', 'status']);
            });
        }

        if (! Schema::hasColumn('transactions', 'copy_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('copy_id')
                    ->nullable()
                    ->after('book_id')
                    ->constrained('library_copies')
                    ->nullOnDelete();
                $table->index(['copy_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'copy_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign(['copy_id']);
                $table->dropIndex(['copy_id', 'status']);
                $table->dropColumn('copy_id');
            });
        }

        Schema::dropIfExists('library_copies');
    }
};
