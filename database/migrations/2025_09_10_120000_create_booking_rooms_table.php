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
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('room_type_id')->nullable();

            // Patient snapshot
            $table->string('patient_name');
            $table->string('patient_phone')->nullable();
            $table->string('patient_email')->nullable();
            $table->text('patient_address')->nullable();

            // Booking detail snapshot
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('duration_days');
            $table->decimal('price_per_day', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Payment linkage/status
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('payment_status')->default('pending'); // pending|settlement|capture|failure|cancelled|expire
            $table->string('payment_method')->nullable(); // snap/va etc
            $table->string('bank_code')->nullable();
            $table->string('va_number')->nullable();
            $table->string('transaction_id')->nullable();

            $table->json('additional_data')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['booking_id']);
            $table->index(['user_id']);
            $table->index(['hospital_id']);
            $table->index(['payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_rooms');
    }
};


