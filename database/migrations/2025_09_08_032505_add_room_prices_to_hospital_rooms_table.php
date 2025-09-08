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
            $table->decimal('vvip_price_per_day', 10, 2)->default(800000)->after('vvip_rooms');
            $table->decimal('class1_price_per_day', 10, 2)->default(500000)->after('class1_rooms');
            $table->decimal('class2_price_per_day', 10, 2)->default(300000)->after('class2_rooms');
            $table->decimal('class3_price_per_day', 10, 2)->default(200000)->after('class3_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_rooms', function (Blueprint $table) {
            $table->dropColumn([
                'vvip_price_per_day',
                'class1_price_per_day', 
                'class2_price_per_day',
                'class3_price_per_day'
            ]);
        });
    }
};