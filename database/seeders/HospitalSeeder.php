<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            [
                'name' => 'RSUD Sidoarjo',
                'slug' => 'rsud-sidoarjo',
                'address' => 'Jl. Mojopahit No.667, Sidowayah, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61215',
                'description' => 'Rumah Sakit Umum Daerah Kabupaten Sidoarjo berdiri pada tanggal 17 Agustus 1956. Rumah Sakit untuk memiliki akreditasi internasional dalam pelayanan, pendidikan dan penelitian.',
                'public_service' => 'RSUD Sidoarjo menyediakan beragam layanan kesehatan mulai dari pelayanan dasar hingga spesialis, termasuk layanan gawat darurat yang siap melayani 24 jam sehari.',
                'image_url' => 'images/hospitals/rsud-sidoarjo.jpg',
                'website_url' => 'https://rsud.sidoarjokab.go.id/'
            ],
            [
                'name' => 'RSUD Dr. Mohammad Soewandhie',
                'slug' => 'rsud-dr-mohammad-soewandhie',
                'address' => 'Jl. Tambak Rejo No.45-47, Tambakrejo, Kec. Simokerto, Surabaya, Jawa Timur 60142',
                'description' => 'RSUD Dr. Mohammad Soewandhie adalah rumah sakit umum daerah yang melayani masyarakat Surabaya dan sekitarnya.',
                'public_service' => 'Menyediakan pelayanan kesehatan yang berkualitas dan terjangkau untuk masyarakat.',
                'image_url' => 'images/hospitals/rsud-soewandhie.jpg',
                'website_url' => 'https://rs-soewandhie.surabaya.go.id/'
            ],
            [
                'name' => 'RSUD Dr Wahidin Sudiro Husodo',
                'slug' => 'rsud-dr-wahidin-sudiro-husodo',
                'address' => 'Jl.Sudirohusodo No.170, Mergelo, Sukoharjo, Kec. Drajat Kulon, Kabupaten Mojokerto, Jawa Timur 61328',
                'description' => 'RSUD Dr Wahidin Sudiro Husodo adalah rumah sakit umum yang melayani masyarakat di wilayah Mojokerto dan sekitarnya.',
                'public_service' => 'Memberikan pelayanan kesehatan yang komprehensif dan berkualitas untuk semua lapisan masyarakat.',
                'image_url' => 'images/hospitals/rsud-wahidin.jpg',
                'website_url' => 'https://rsudsurabaya.com/'
            ],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::create($hospital);
        }
    }
}
