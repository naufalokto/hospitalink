<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\HospitalRoom;

class AssignFacilityToHospitalRoomsSeeder extends Seeder
{
    /**
     * Map first three hospitals to facility IDs 1..3.
     */
    public function run(): void
    {
        $hospitals = Hospital::orderBy('id')->take(3)->get();

        foreach ($hospitals as $index => $hospital) {
            $facilityId = $index + 1; // 1,2,3
            $room = HospitalRoom::firstOrCreate(
                ['hospital_id' => $hospital->id],
                [
                    'vvip_rooms' => 10,
                    'class1_rooms' => 10,
                    'class2_rooms' => 10,
                    'class3_rooms' => 10,
                    'vvip_price_per_day' => 800000,
                    'class1_price_per_day' => 500000,
                    'class2_price_per_day' => 300000,
                    'class3_price_per_day' => 200000,
                ]
            );

            $room->facility_id = $facilityId;
            $room->save();
        }
    }
}


