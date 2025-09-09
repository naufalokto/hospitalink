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
        // Drop hospital_rooms table if it exists
        Schema::dropIfExists('hospital_rooms');
    }

    /**
     * Reverse the migrations.
     */
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
            $table->decimal('vvip_price_per_day', 10, 2)->default(0);
            $table->decimal('class1_price_per_day', 10, 2)->default(0);
            $table->decimal('class2_price_per_day', 10, 2)->default(0);
            $table->decimal('class3_price_per_day', 10, 2)->default(0);
            $table->foreignId('facility_id')->nullable()->constrained('facilities');
            $table->timestamps();
            
            $table->unique('hospital_id');
        });
    }
};