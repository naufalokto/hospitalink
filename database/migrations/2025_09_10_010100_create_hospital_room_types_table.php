<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospital_room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->unsignedInteger('rooms_count')->default(0);
            $table->decimal('price_per_day', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['hospital_id', 'room_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_room_types');
    }
};


