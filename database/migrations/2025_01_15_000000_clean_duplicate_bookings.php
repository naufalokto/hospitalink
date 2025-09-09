<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clean up duplicate bookings - keep only the latest booking per user per hospital
        DB::statement("
            DELETE b1 FROM bookings b1
            INNER JOIN bookings b2 
            WHERE b1.user_id = b2.user_id 
            AND b1.hospital_id = b2.hospital_id 
            AND b1.id < b2.id
        ");
    }

    public function down(): void
    {
        // Cannot restore deleted data
    }
};
