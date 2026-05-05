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
        Schema::create('sms_otps', function (Blueprint $table) {
            $table->id();
            $table->string('otp');
            $table->string('phone');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('used')->default(false);
            $table->dateTime('expires_at');
            $table->integer('attempts')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_SmsOtps');
    }
};
