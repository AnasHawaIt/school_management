<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['status', 'due_date'], 'transactions_status_due_date_index');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['status', 'scheduled_at'], 'announcements_status_scheduled_at_index');
            $table->index(['status', 'expires_at'], 'announcements_status_expires_at_index');
            $table->index('title', 'announcements_title_index');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index('title', 'books_title_index');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_status_scheduled_at_index');
            $table->dropIndex('announcements_status_expires_at_index');
            $table->dropIndex('announcements_title_index');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('books_title_index');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_status_due_date_index');
        });
    }
};
