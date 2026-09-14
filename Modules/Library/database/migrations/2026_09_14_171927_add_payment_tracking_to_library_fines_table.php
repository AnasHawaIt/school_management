<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_fines', function (Blueprint $table) {

            $table->foreignId('paid_by')
                ->nullable()
                ->after('paid_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('waived_by')
                ->nullable()
                ->after('waived_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('library_fines', function (Blueprint $table) {

            $table->dropForeign(['paid_by']);
            $table->dropForeign(['waived_by']);

            $table->dropColumn([
                'paid_by',
                'waived_by',
            ]);
        });
    }
};
