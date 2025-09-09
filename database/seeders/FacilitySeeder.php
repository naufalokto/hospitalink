<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Seed the facilities table with exactly three records.
     */
    public function run(): void
    {
        // Keep exactly three facility groups, each with a list of items
        $facilities = [
            1 => [
                'facility' => 'VVIP Facility',
                'items' => [
                    '1 Bed Pasien',
                    '1 Ruang Keluarga/Pribadi',
                    '1 Lemari ES',
                    '1 Kamar mandi',
                    'Wastafel',
                    'AC',
                    'TV',
                    'Wi-Fi',
                    'Nurse Call'
                ],
            ],
            2 => [
                'facility' => 'Class 1 Facility',
                'items' => [
                    '4 Bed Pasien',
                    '4 Meja',
                    '4 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV',
                    'Wi-Fi'
                ],
            ],
            3 => [
                'facility' => 'Class 2/3 Facility',
                'items' => [
                    '6-8 Bed Pasien',
                    'Meja bersama',
                    'Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    'TV bersama'
                ],
            ],
        ];

        foreach ($facilities as $id => $payload) {
            Facility::updateOrCreate(
                ['id' => $id],
                $payload
            );
        }

        // Optionally remove any extra facilities beyond the three we want
        Facility::whereNotIn('id', [1, 2, 3])->delete();
    }
}


