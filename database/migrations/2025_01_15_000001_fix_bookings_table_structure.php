<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add foreign key constraint if room_type_id exists but doesn't have constraint
            if (Schema::hasColumn('bookings', 'room_type_id')) {
                // Check if foreign key constraint already exists
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'bookings' 
                    AND COLUMN_NAME = 'room_type_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (empty($foreignKeys)) {
                    $table->foreign('room_type_id')->references('id')->on('room_types');
                }
            } else {
                // Add room_type_id column if it doesn't exist
                $table->foreignId('room_type_id')->nullable()->after('hospital_id')->constrained('room_types');
            }
            
            // Remove redundant room_name column if it exists
            if (Schema::hasColumn('bookings', 'room_name')) {
                $table->dropColumn('room_name');
            }
            
            // Add unique constraint to ensure 1 user can only book 1 room per hospital
            $table->unique(['user_id', 'hospital_id'], 'user_hospital_unique_booking');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // First drop foreign key constraints that depend on the unique index
            $table->dropForeign(['user_id']);
            $table->dropForeign(['hospital_id']);
            
            // Now we can safely drop the unique constraint
            $table->dropUnique('user_hospital_unique_booking');
            
            // Re-add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
            
            // Add back room_name column
            $table->string('room_name')->after('room_type');
            
            // Drop foreign key
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
        });
    }
};
