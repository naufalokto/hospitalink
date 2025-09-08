<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\HospitalRoom;

class HospitalRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing hospitals
        $hospitals = Hospital::all();

        foreach ($hospitals as $hospital) {
            // Check if room data already exists
            if (!$hospital->room) {
                HospitalRoom::create([
                    'hospital_id' => $hospital->id,
                    'vvip_rooms' => 10,
                    'class1_rooms' => 10,
                    'class2_rooms' => 10,
                    'class3_rooms' => 10,
                    'vvip_price_per_day' => 800000,
                    'class1_price_per_day' => 500000,
                    'class2_price_per_day' => 300000,
                    'class3_price_per_day' => 200000,
                ]);
            }
        }
    }
}
