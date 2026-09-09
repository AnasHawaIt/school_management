<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedInteger('max_active_loans')->nullable()->after('status');
        });

        Schema::table('library_copies', function (Blueprint $table) {
            $table->decimal('replacement_cost', 10, 2)->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('library_copies', fn (Blueprint $table) => $table->dropColumn('replacement_cost'));
        Schema::table('members', fn (Blueprint $table) => $table->dropColumn('max_active_loans'));
    }
};
