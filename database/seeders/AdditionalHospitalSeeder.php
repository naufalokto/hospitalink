<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\RoomType;
use App\Models\HospitalRoomType;

class AdditionalHospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all room types
        $roomTypes = RoomType::all();
        
        if ($roomTypes->isEmpty()) {
            $this->command->error('No room types found. Please run RoomTypeSeeder first.');
            return;
        }

        // Create 2 additional hospitals with empty data
        $hospitals = [
            [
                'name' => 'Rumah Sakit Islam Surabaya (RSIS A. Yani)',
                'slug' => 'rumahsakit-islam-surabaya',
                'address' => 'Jl. Jenderal A. Yani No. 2-4, Wonokromo, Kota Surabaya, Jawa Timur',
                'description' => 'Rumah Sakit Islam Surabaya (RSIS) berdiri pada 25 Maret 1975 di bawah naungan Yayasan Rumah Sakit Islam Surabaya. Sebagai rumah sakit umum kelas B, RSIS memiliki visi memberikan pelayanan kesehatan yang Islami, profesional, dan berorientasi pada kepuasan masyarakat. Dengan luas tanah sekitar 8.272 m² dan bangunan lebih dari 23.000 m², rumah sakit ini menjadi salah satu pusat layanan kesehatan rujukan di Kota Surabaya.',
                'public_service' => '<p>RSIS menyediakan pelayanan medis yang komprehensif, mulai dari layanan dasar hingga subspesialis. Layanan yang tersedia meliputi instalasi gawat darurat 24 jam, poliklinik umum dan spesialis, pelayanan ibu dan anak, penyakit dalam, bedah, anak, jantung dan pembuluh darah, paru, urologi, radiologi, rehabilitasi medik, serta layanan psikologi dan psikiatri. Fasilitas penunjang seperti laboratorium, farmasi, serta instalasi rawat jalan dan rawat inap mendukung kualitas pelayanan yang diberikan. Dengan tenaga medis profesional dan lebih dari 500 staf, RSIS berkomitmen memberikan pelayanan berorientasi mutu dan keselamatan pasien. <strong>PERSYARATAN RAWAT INAP</strong> Kartu Identitas / KTP (Kartu Tanda Penduduk), Kartu BPJS (jika ada), Kartu Pasien RSIS, Bukti Pembayaran atau Jaminan dari Penjamin. <strong>HARGA RAWAT INAP</strong> VVIP = Rp 750.000,- Per hari*, Kelas 1 = Rp 235.000,- Per hari*, Kelas 2 = Rp 167.000,- Per hari*, Kelas 3 = Rp 115.000,- Per hari* (* : tidak pakai BPJS). <strong>FASILITAS</strong> Instalasi Rawat Inap dan Rawat Jalan, Instalasi Gawat Darurat (IGD) 24 jam, Poliklinik Spesialis & Subspesialis, Laboratorium & Radiologi, ICU, NICU, PICU, HCU, Perinatologi, Fasilitas Rehabilitasi Medik, Ruang Operasi & Kamar Bersalin, Farmasi & Apotek. <strong>WEBSITE</strong> <a href="https://rsisurabaya.com">https://rsisurabaya.com</a>. <strong>NOMOR TELEPON</strong> (031) 8284505. <strong>ALAMAT</strong> Jl. Jenderal A. Yani No. 2-4, Wonokromo, Kota Surabaya, Jawa Timur.</p>',
                'image_url' => 'images/hospitals/rsis_ayani.jpg',
                'website_url' => 'https://rsisurabaya.com',
            ],
            [
                'name' => 'Mayapada Hospital Surabaya (MHSB)',
                'slug' => 'mayapada-hospital-surabaya',
                'address' => 'Jl. Mayjen Sungkono No.16-20, Pakis, Kec. Sawahan, Kota Surabaya, Jawa Timur 60256',
                'description' => 'Mayapada Hospital Surabaya resmi beroperasi sejak 22 November 2021 sebagai bagian dari jaringan Mayapada Healthcare Group. Rumah sakit ini hadir sebagai rumah sakit umum kelas C dengan visi memberikan layanan kesehatan modern, profesional, dan berstandar internasional. Berlokasi strategis di kawasan Mayjen Sungkono, Surabaya, MHSB dibangun di atas lahan seluas 3.483 m² dengan luas bangunan mencapai 24.214 m².',
                'public_service' => '<p>Mayapada Hospital Surabaya menyediakan layanan kesehatan komprehensif dengan tenaga medis berpengalaman serta fasilitas berteknologi tinggi. Layanan unggulan meliputi Trauma Center, Cardiac Emergency, dan Stroke Emergency yang siap 24 jam. Selain itu, rumah sakit ini juga mengembangkan konsep Center of Excellence (COE) dalam berbagai bidang spesialis seperti Tahir Neuroscience Center, Cardiovascular Center, Gastrohepatology Center, Orthopedic Center, Uro-Nephrology Center, Oncology Center, Women & Children Center, hingga layanan Medical Check Up terpadu. Dengan komitmen pada keselamatan pasien, MHSB menghadirkan ruang operasi hybrid serta ICU Solitaire yang memberikan kenyamanan dan keamanan lebih bagi pasien. <strong>PERSYARATAN RAWAT INAP</strong> Kartu Identitas / KTP (Kartu Tanda Penduduk), Kartu BPJS (jika ada), Kartu Pasien, Bukti Pembayaran atau Surat Jaminan Penjamin. <strong>HARGA RAWAT INAP</strong> VVIP / Presidential Suite = Rp 1.345.000,- Per hari*, Kelas 1 = Rp 750.000,- Per hari*, Kelas 2 = Rp 530.000,- Per hari*, Kelas 3 = Rp 267.000,- Per hari* (* : tidak pakai BPJS). <strong>FASILITAS</strong> Instalasi Rawat Inap, Instalasi Rawat Jalan, Instalasi Gawat Darurat (IGD) 24 Jam, Laboratorium Klinik (BSL-2), Radiologi (CT-Scan 128 slice, MRI 1.5T), Endoskopi & Kateterisasi Jantung, Operating Theater & Hybrid Operating Room, ICU, HCU, NICU, PICU, Perinatologi, Ruang Isolasi, Farmasi & Apotek, Medical Check Up Center. <strong>WEBSITE</strong> <a href="https://mayapadahospital.com">https://mayapadahospital.com</a>. <strong>NOMOR TELPON</strong> (031) 7300 999. <strong>ALAMAT</strong> Jl. Mayjen Sungkono No.16-20, Pakis, Kec. Sawahan, Kota Surabaya, Jawa Timur 60256.</p>',
                'image_url' => 'images/hospitals/mayapada_hospital.jpg',
                'website_url' => 'https://mayapadahospital.com',
            ]
        ];

        foreach ($hospitals as $hospitalData) {
            // Check if hospital already exists
            $existingHospital = Hospital::where('slug', $hospitalData['slug'])->first();
            
            if ($existingHospital) {
                $this->command->info("Hospital already exists: {$existingHospital->name} (ID: {$existingHospital->id})");
                continue;
            }

            // Create hospital
            $hospital = Hospital::create($hospitalData);
            
            $this->command->info("Created hospital: {$hospital->name} (ID: {$hospital->id})");

            // Create hospital room types for each room type with default values
            foreach ($roomTypes as $roomType) {
                // Check if hospital room type already exists
                $existingHospitalRoomType = HospitalRoomType::where('hospital_id', $hospital->id)
                    ->where('room_type_id', $roomType->id)
                    ->first();
                
                if ($existingHospitalRoomType) {
                    $this->command->info("  - Room type already exists: {$roomType->name}");
                    continue;
                }

                HospitalRoomType::create([
                    'hospital_id' => $hospital->id,
                    'room_type_id' => $roomType->id,
                    'rooms_count' => 0, // Default empty - to be filled by team
                    'price_per_day' => 0, // Default empty - to be filled by team
                ]);
                
                $this->command->info("  - Created room type: {$roomType->name} (Default: 0 rooms, 0 price)");
            }
        }

        $this->command->info('✅ Successfully created 2 additional hospitals with empty data');
        $this->command->info('📝 Note: Room counts and prices are set to 0 - to be filled by development team');
    }
}