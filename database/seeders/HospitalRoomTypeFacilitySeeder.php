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
        
        // Define facilities for each room type
        $roomTypeFacilities = [
            'vvip' => [
                'AC', 'TV', 'Wi-Fi', 'Kamar mandi dalam', 'Nurse Call', 
                'Lemari', 'Meja', 'Kursi', 'Wastafel', 'Lemari ES', 
                'Ruang keluarga', 'Bed pasien', 'Telepon', 'Kulkas', 
                'Sofa', 'Karpet', 'Lampu baca', 'Ventilasi', 'Pintu otomatis'
            ],
            'class1' => [
                'AC', 'TV', 'Wi-Fi', 'Kamar mandi dalam', 'Nurse Call', 
                'Lemari', 'Meja', 'Kursi', 'Wastafel', 'Bed pasien', 
                'Telepon', 'Lampu baca', 'Ventilasi'
            ],
            'class2' => [
                'AC', 'Wi-Fi', 'Kamar mandi dalam', 'Nurse Call', 
                'Lemari', 'Meja', 'Kursi', 'Wastafel', 'Bed pasien', 
                'Lampu baca', 'Ventilasi'
            ],
            'class3' => [
                'AC', 'Kamar mandi dalam', 'Nurse Call', 
                'Lemari', 'Meja', 'Kursi', 'Wastafel', 'Bed pasien', 
                'Ventilasi'
            ],
        ];
        
        foreach ($hospitals as $hospital) {
            foreach ($roomTypes as $roomType) {
                $hospitalRoomType = HospitalRoomType::where('hospital_id', $hospital->id)
                    ->where('room_type_id', $roomType->id)
                    ->first();
                
                if ($hospitalRoomType && isset($roomTypeFacilities[$roomType->code])) {
                    $facilities = $roomTypeFacilities[$roomType->code];
                    
                    foreach ($facilities as $facilityName) {
                        $facility = Facility::where('facility', $facilityName)->first();
                        if ($facility) {
                            $hospitalRoomType->facilities()->syncWithoutDetaching([$facility->id]);
                        }
                    }
                }
            }
        }
    }
}