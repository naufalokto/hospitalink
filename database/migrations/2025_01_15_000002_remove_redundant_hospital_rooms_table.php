<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the redundant hospital_rooms table
        // The room count is already stored in hospital_room_types.rooms_count
        Schema::dropIfExists('hospital_rooms');
    }

    public function down(): void
    {
        // Recreate hospital_rooms table if needed to rollback
        Schema::create('hospital_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->integer('vvip_rooms')->default(0);
            $table->integer('class1_rooms')->default(0);
            $table->integer('class2_rooms')->default(0);
            $table->integer('class3_rooms')->default(0);
            $table->timestamps();
            
            $table->unique('hospital_id');
        });
    }
};
