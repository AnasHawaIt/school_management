<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('borrow_date');
            $table->timestamp('returned_at')->nullable()->after('return_date');
            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['status', 'due_date']);
            $table->dropColumn(['due_date', 'returned_at']);
        });
    }
};
