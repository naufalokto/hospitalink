<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\RoomType;
use App\Models\Facility;
use App\Models\HospitalRoomType;

class HospitalRoomTypeFacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Get all hospitals and room types
        $hospitals = Hospital::all();
        $roomTypes = RoomType::all();
        
        // Map room types to their corresponding facilities
        $roomTypeToFacility = [
            'vvip' => 'VVIP Facility',
            'class1' => 'Class 1 Facility',
            'class2' => 'Class 2/3 Facility',
            'class3' => 'Class 2/3 Facility',
        ];
        
        foreach ($hospitals as $hospital) {
            foreach ($roomTypes as $roomType) {
                $hospitalRoomType = HospitalRoomType::where('hospital_id', $hospital->id)
                    ->where('room_type_id', $roomType->id)
                    ->first();
                
                if ($hospitalRoomType && isset($roomTypeToFacility[$roomType->code])) {
                    $facilityName = $roomTypeToFacility[$roomType->code];
                    $facility = Facility::where('facility', $facilityName)->first();
                    
                    if ($facility) {
                        // Attach the facility to this hospital room type
                        $hospitalRoomType->facilities()->syncWithoutDetaching([$facility->id]);
                    }
                }
            }
        }
    }
}