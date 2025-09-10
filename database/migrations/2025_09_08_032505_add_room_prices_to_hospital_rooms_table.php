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
        // Check if hospital_rooms table exists before trying to modify it
        if (Schema::hasTable('hospital_rooms')) {
            Schema::table('hospital_rooms', function (Blueprint $table) {
                // Check if columns don't already exist
                if (!Schema::hasColumn('hospital_rooms', 'vvip_price_per_day')) {
                    $table->decimal('vvip_price_per_day', 10, 2)->default(800000)->after('vvip_rooms');
                }
                if (!Schema::hasColumn('hospital_rooms', 'class1_price_per_day')) {
                    $table->decimal('class1_price_per_day', 10, 2)->default(500000)->after('class1_rooms');
                }
                if (!Schema::hasColumn('hospital_rooms', 'class2_price_per_day')) {
                    $table->decimal('class2_price_per_day', 10, 2)->default(300000)->after('class2_rooms');
                }
                if (!Schema::hasColumn('hospital_rooms', 'class3_price_per_day')) {
                    $table->decimal('class3_price_per_day', 10, 2)->default(200000)->after('class3_rooms');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if hospital_rooms table exists before trying to modify it
        if (Schema::hasTable('hospital_rooms')) {
            Schema::table('hospital_rooms', function (Blueprint $table) {
                // Check if columns exist before dropping them
                if (Schema::hasColumn('hospital_rooms', 'vvip_price_per_day')) {
                    $table->dropColumn('vvip_price_per_day');
                }
                if (Schema::hasColumn('hospital_rooms', 'class1_price_per_day')) {
                    $table->dropColumn('class1_price_per_day');
                }
                if (Schema::hasColumn('hospital_rooms', 'class2_price_per_day')) {
                    $table->dropColumn('class2_price_per_day');
                }
                if (Schema::hasColumn('hospital_rooms', 'class3_price_per_day')) {
                    $table->dropColumn('class3_price_per_day');
                }
            });
        }
    }
};