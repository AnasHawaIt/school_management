<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY-2026-00001
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('student_fee_id')->constrained('student_fees')->onDelete('cascade');
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'bank_transfer', 'stripe', 'paypal'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            // Bank transfer
            $table->string('bank_name')->nullable();
            $table->string('transfer_reference')->nullable();
            $table->date('transfer_date')->nullable();
            // Online payment
            $table->string('transaction_id')->nullable();
            $table->string('gateway_response')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['student_id', 'status', 'paid_at']);
            $table->index('payment_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
