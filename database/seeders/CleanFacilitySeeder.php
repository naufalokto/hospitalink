<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class CleanFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'AC',
            'TV',
            'Wi-Fi',
            'Kamar mandi dalam',
            'Nurse Call',
            'Lemari',
            'Meja',
            'Kursi',
            'Wastafel',
            'Lemari ES',
            'Ruang keluarga',
            'Bed pasien',
            'Telepon',
            'Kulkas',
            'Sofa',
            'Karpet',
            'Lampu baca',
            'Ventilasi',
            'Pintu otomatis',
            'Lift akses',
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['facility' => $facility],
                ['facility' => $facility]
            );
        }
    }
}
