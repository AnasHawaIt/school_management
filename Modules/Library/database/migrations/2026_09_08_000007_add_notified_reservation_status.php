<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE library_reservations MODIFY status ENUM('pending', 'notified', 'fulfilled', 'cancelled', 'expired') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('library_reservations', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('library_reservations', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE library_reservations MODIFY status ENUM('pending', 'fulfilled', 'cancelled', 'expired') NOT NULL DEFAULT 'pending'");
        }
    }
};
