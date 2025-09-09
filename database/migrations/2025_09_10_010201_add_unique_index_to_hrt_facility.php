<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hospital_room_type_facility')) {
            return; // created by previous migration
        }

        // Check if the unique index already exists
        $exists = collect(DB::select("SHOW INDEX FROM hospital_room_type_facility"))
            ->contains(function ($idx) {
                return ($idx->Key_name ?? '') === 'hrt_facility_unique';
            });

        if (!$exists) {
            Schema::table('hospital_room_type_facility', function (Blueprint $table) {
                $table->unique(['hospital_room_type_id', 'facility_id'], 'hrt_facility_unique');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('hospital_room_type_facility')) {
            return;
        }

        // Drop the unique index if it exists
        $exists = collect(DB::select("SHOW INDEX FROM hospital_room_type_facility"))
            ->contains(function ($idx) {
                return ($idx->Key_name ?? '') === 'hrt_facility_unique';
            });

        if ($exists) {
            Schema::table('hospital_room_type_facility', function (Blueprint $table) {
                $table->dropUnique('hrt_facility_unique');
            });
        }
    }
};


