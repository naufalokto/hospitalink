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
        Schema::table('hospital_rooms', function (Blueprint $table) {
            $table->foreignId('facility_id')->nullable()->constrained('facilities')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('facility_id');
        });
    }
};


