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
        // Add indexes to users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('email'); // For login queries
                $table->index('role'); // For admin/patient filtering
                $table->index('google_id'); // For OAuth queries
                $table->index('facebook_id'); // For OAuth queries
                $table->index('twitter_id'); // For OAuth queries
                $table->index(['role', 'created_at']); // For user management queries
            });
        }

        // Add indexes to hospitals table
        if (Schema::hasTable('hospitals')) {
            Schema::table('hospitals', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('slug'); // For hospital lookup by slug
                $table->index('name'); // For hospital search
                $table->index('created_at'); // For sorting by creation date
                $table->index(['slug', 'name']); // Composite for hospital filtering
            });
        }

        // Add indexes to bookings table
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('booking_number'); // For booking lookup
                $table->index('user_id'); // For user's bookings
                $table->index('hospital_id'); // For hospital's bookings
                $table->index('room_type_id'); // For room type filtering
                $table->index('status'); // For status filtering
                $table->index('check_in_date'); // For date range queries
                $table->index('check_out_date'); // For date range queries
                $table->index('created_at'); // For sorting by creation date
                
                // Composite indexes for complex queries
                $table->index(['user_id', 'status']); // User's bookings by status
                $table->index(['hospital_id', 'status']); // Hospital's bookings by status
                $table->index(['hospital_id', 'room_type_id']); // Hospital room availability
                $table->index(['status', 'created_at']); // Recent bookings by status
                $table->index(['check_in_date', 'check_out_date']); // Date range queries
                $table->index(['user_id', 'hospital_id', 'status']); // User's hospital bookings
            });
        }

        // Add indexes to payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('order_id'); // For Midtrans integration
                $table->index('booking_id'); // For booking-payment relationship
                $table->index('status'); // For payment status filtering
                $table->index('payment_type'); // For payment method filtering
                $table->index('transaction_id'); // For transaction lookup
                $table->index('expired_at'); // For expired payment cleanup
                $table->index('created_at'); // For sorting by creation date
                
                // Composite indexes
                $table->index(['booking_id', 'status']); // Booking payments by status
                $table->index(['status', 'created_at']); // Recent payments by status
                $table->index(['payment_type', 'status']); // Payment method by status
            });
        }

        // Add indexes to transaction_details table
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('transaction_number'); // For transaction lookup
                $table->index('payment_id'); // For payment-transaction relationship
                $table->index('user_id'); // For user's transactions
                $table->index('hospital_id'); // For hospital's transactions
                $table->index('room_type_id'); // For room type filtering
                $table->index('status'); // For status filtering
                $table->index('payment_completed_at'); // For completed transactions
                $table->index('created_at'); // For sorting by creation date
                
                // Composite indexes
                $table->index(['user_id', 'status']); // User's transactions by status
                $table->index(['hospital_id', 'status']); // Hospital's transactions by status
                $table->index(['status', 'payment_completed_at']); // Completed transactions by date
                $table->index(['user_id', 'payment_completed_at']); // User's completed transactions
            });
        }

        // Add indexes to booking_rooms table
        if (Schema::hasTable('booking_rooms')) {
            Schema::table('booking_rooms', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('booking_id'); // For booking relationship
                $table->index('user_id'); // For user's booking rooms
                $table->index('hospital_id'); // For hospital's booking rooms
                $table->index('room_type_id'); // For room type filtering
                $table->index('payment_status'); // For payment status filtering
                $table->index('payment_id'); // For payment relationship
                $table->index('created_at'); // For sorting by creation date
                
                // Composite indexes
                $table->index(['user_id', 'payment_status']); // User's booking rooms by payment status
                $table->index(['hospital_id', 'payment_status']); // Hospital's booking rooms by payment status
                $table->index(['payment_status', 'created_at']); // Recent booking rooms by payment status
            });
        }

        // Add indexes to hospital_room_types table
        if (Schema::hasTable('hospital_room_types')) {
            Schema::table('hospital_room_types', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('hospital_id'); // For hospital's room types
                $table->index('room_type_id'); // For room type's hospitals
                $table->index('rooms_count'); // For room availability queries
                $table->index('price_per_day'); // For price range queries
                
                // Composite indexes
                $table->index(['hospital_id', 'room_type_id']); // Hospital-room type relationship
                $table->index(['room_type_id', 'rooms_count']); // Room availability by type
                $table->index(['hospital_id', 'rooms_count']); // Hospital room availability
            });
        }

        // Add indexes to room_types table
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('code'); // For room type lookup by code
                $table->index('name'); // For room type search
            });
        }

        // Add indexes to facilities table
        if (Schema::hasTable('facilities')) {
            Schema::table('facilities', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('facility'); // For facility search
            });
        }

        // Add indexes to hospital_room_type_facility table
        if (Schema::hasTable('hospital_room_type_facility')) {
            Schema::table('hospital_room_type_facility', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('hospital_room_type_id'); // For hospital room type relationship
                $table->index('facility_id'); // For facility relationship
                
                // Composite index for many-to-many relationship
                $table->index(['hospital_room_type_id', 'facility_id']); // Unique relationship
            });
        }

        // Add indexes to news table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                // Add indexes for common queries
                $table->index('slug'); // For news lookup by slug
                $table->index('created_at'); // For sorting by creation date
                $table->index(['slug', 'created_at']); // For news filtering
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email']);
                $table->dropIndex(['role']);
                $table->dropIndex(['google_id']);
                $table->dropIndex(['facebook_id']);
                $table->dropIndex(['twitter_id']);
                $table->dropIndex(['role', 'created_at']);
            });
        }

        // Drop indexes from hospitals table
        if (Schema::hasTable('hospitals')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->dropIndex(['slug']);
                $table->dropIndex(['name']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['slug', 'name']);
            });
        }

        // Drop indexes from bookings table
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex(['booking_number']);
                $table->dropIndex(['user_id']);
                $table->dropIndex(['hospital_id']);
                $table->dropIndex(['room_type_id']);
                $table->dropIndex(['status']);
                $table->dropIndex(['check_in_date']);
                $table->dropIndex(['check_out_date']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['user_id', 'status']);
                $table->dropIndex(['hospital_id', 'status']);
                $table->dropIndex(['hospital_id', 'room_type_id']);
                $table->dropIndex(['status', 'created_at']);
                $table->dropIndex(['check_in_date', 'check_out_date']);
                $table->dropIndex(['user_id', 'hospital_id', 'status']);
            });
        }

        // Drop indexes from payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex(['order_id']);
                $table->dropIndex(['booking_id']);
                $table->dropIndex(['status']);
                $table->dropIndex(['payment_type']);
                $table->dropIndex(['transaction_id']);
                $table->dropIndex(['expired_at']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['booking_id', 'status']);
                $table->dropIndex(['status', 'created_at']);
                $table->dropIndex(['payment_type', 'status']);
            });
        }

        // Drop indexes from transaction_details table
        if (Schema::hasTable('transaction_details')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropIndex(['transaction_number']);
                $table->dropIndex(['payment_id']);
                $table->dropIndex(['user_id']);
                $table->dropIndex(['hospital_id']);
                $table->dropIndex(['room_type_id']);
                $table->dropIndex(['status']);
                $table->dropIndex(['payment_completed_at']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['user_id', 'status']);
                $table->dropIndex(['hospital_id', 'status']);
                $table->dropIndex(['status', 'payment_completed_at']);
                $table->dropIndex(['user_id', 'payment_completed_at']);
            });
        }

        // Drop indexes from booking_rooms table
        if (Schema::hasTable('booking_rooms')) {
            Schema::table('booking_rooms', function (Blueprint $table) {
                $table->dropIndex(['booking_id']);
                $table->dropIndex(['user_id']);
                $table->dropIndex(['hospital_id']);
                $table->dropIndex(['room_type_id']);
                $table->dropIndex(['payment_status']);
                $table->dropIndex(['payment_id']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['user_id', 'payment_status']);
                $table->dropIndex(['hospital_id', 'payment_status']);
                $table->dropIndex(['payment_status', 'created_at']);
            });
        }

        // Drop indexes from hospital_room_types table
        if (Schema::hasTable('hospital_room_types')) {
            Schema::table('hospital_room_types', function (Blueprint $table) {
                $table->dropIndex(['hospital_id']);
                $table->dropIndex(['room_type_id']);
                $table->dropIndex(['rooms_count']);
                $table->dropIndex(['price_per_day']);
                $table->dropIndex(['hospital_id', 'room_type_id']);
                $table->dropIndex(['room_type_id', 'rooms_count']);
                $table->dropIndex(['hospital_id', 'rooms_count']);
            });
        }

        // Drop indexes from room_types table
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->dropIndex(['code']);
                $table->dropIndex(['name']);
            });
        }

        // Drop indexes from facilities table
        if (Schema::hasTable('facilities')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->dropIndex(['facility']);
            });
        }

        // Drop indexes from hospital_room_type_facility table
        if (Schema::hasTable('hospital_room_type_facility')) {
            Schema::table('hospital_room_type_facility', function (Blueprint $table) {
                $table->dropIndex(['hospital_room_type_id']);
                $table->dropIndex(['facility_id']);
                $table->dropIndex(['hospital_room_type_id', 'facility_id']);
            });
        }

        // Drop indexes from news table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex(['slug']);
                $table->dropIndex(['created_at']);
                $table->dropIndex(['slug', 'created_at']);
            });
        }
    }
};
