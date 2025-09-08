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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // Midtrans order ID
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('payment_type'); // bank_transfer, ewallet, etc
            $table->string('bank_code')->nullable(); // BCA, BRI, BNI, etc
            $table->string('va_number')->nullable(); // Virtual Account Number
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'failure'])->default('pending');
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            $table->json('midtrans_response')->nullable(); // Store full Midtrans response
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
