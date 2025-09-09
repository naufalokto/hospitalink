<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove incorrect facility entries (VVIP Facility, Class 1 Facility, Class 2/3 Facility)
        // These are not individual facilities but room type descriptions
        DB::table('facilities')->whereIn('id', [1, 2, 3])->delete();
        
        // Remove the items column as it's not needed in the normalized design
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('items');
        });
        
        // Reset auto increment to start from 1
        DB::statement('ALTER TABLE facilities AUTO_INCREMENT = 1');
    }

    public function down(): void
    {
        // Add back items column
        Schema::table('facilities', function (Blueprint $table) {
            $table->json('items')->nullable();
        });
        
        // Restore the deleted facilities (this is just for rollback, data will be lost)
        DB::table('facilities')->insert([
            [
                'id' => 1,
                'facility' => 'VVIP Facility',
                'items' => json_encode(['1 Bed Pasien', '1 Ruang Keluarga Pribadi', '1 Lemari']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'facility' => 'Class 1 Facility',
                'items' => json_encode(['4 Bed Pasien', '4 Meja', '4 Kursi', '1 AC', '1 Kamar']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'facility' => 'Class 2/3 Facility',
                'items' => json_encode(['6-8 Bed Pasien', 'Meja bersama', 'Kursi', '1 AC']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
};
