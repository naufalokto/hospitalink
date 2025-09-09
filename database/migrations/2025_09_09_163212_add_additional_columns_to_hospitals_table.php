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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->text('admission_requirements')->nullable()->after('public_service');
            $table->text('room_prices')->nullable()->after('admission_requirements');
            $table->string('phone_number')->nullable()->after('room_prices');
            $table->text('facilities')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn(['admission_requirements', 'room_prices', 'phone_number', 'facilities']);
        });
    }
};