<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hospital_room_type_facility')) {
            return; // Table already exists from a previous partial run
        }

        Schema::create('hospital_room_type_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_room_type_id')->constrained('hospital_room_types')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['hospital_room_type_id', 'facility_id'], 'hrt_facility_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_room_type_facility');
    }
};


