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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // Unique transaction number
            $table->foreignId('payment_id')->constrained()->onDelete('cascade'); // Link to payment
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Link to booking
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to user
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade'); // Link to hospital
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade'); // Link to room type
            
            // Transaction details
            $table->string('patient_name');
            $table->string('patient_phone');
            $table->string('patient_email')->nullable();
            $table->text('patient_address')->nullable();
            
            // Room and hospital details
            $table->string('hospital_name');
            $table->string('room_type_name'); // VVIP ROOM, CLASS 1 ROOM, etc.
            $table->string('room_type_code'); // vvip, class1, class2, class3
            
            // Booking dates and duration
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('duration_days');
            
            // Pricing details
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('subtotal', 10, 2); // price_per_day * duration_days
            $table->decimal('total_amount', 10, 2); // Final amount paid
            
            // Payment details
            $table->string('payment_method'); // bank_transfer, ewallet, etc
            $table->string('bank_code')->nullable(); // BCA, BRI, BNI, etc
            $table->string('va_number')->nullable(); // Virtual Account Number
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            
            // Status and metadata
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->timestamp('payment_completed_at')->nullable(); // When payment was completed
            $table->json('additional_data')->nullable(); // Store any additional transaction data
            $table->text('notes')->nullable(); // Additional notes
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['hospital_id', 'status']);
            $table->index(['payment_id']);
            $table->index(['transaction_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};