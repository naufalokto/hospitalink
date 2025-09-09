<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\RoomType;
use App\Models\HospitalRoomType;

class HospitalRoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = Hospital::all();
        $roomTypes = RoomType::all()->keyBy('code');

        foreach ($hospitals as $hospital) {
            foreach (['vvip', 'class1', 'class2', 'class3'] as $code) {
                $rt = $roomTypes[$code] ?? null;
                if (!$rt) { continue; }

                HospitalRoomType::updateOrCreate(
                    [
                        'hospital_id' => $hospital->id,
                        'room_type_id' => $rt->id,
                    ],
                    [
                        'rooms_count' => 10,
                        'price_per_day' => match ($code) {
                            'vvip' => 800000,
                            'class1' => 500000,
                            'class2' => 300000,
                            'class3' => 200000,
                            default => 0,
                        },
                    ]
                );
            }
        }
    }
}


