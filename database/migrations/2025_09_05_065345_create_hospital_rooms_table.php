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
        Schema::create('hospital_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->integer('vvip_rooms')->default(0);
            $table->integer('class1_rooms')->default(0);
            $table->integer('class2_rooms')->default(0);
            $table->integer('class3_rooms')->default(0);
            $table->timestamps();
            
            $table->unique('hospital_id'); // One room record per hospital
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_rooms');
    }
};
