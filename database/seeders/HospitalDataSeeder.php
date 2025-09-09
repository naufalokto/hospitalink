<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\RoomType;
use App\Models\HospitalRoomType;
use Illuminate\Support\Facades\DB;

class HospitalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏥 Updating hospital data with additional information...');

        // Data hospital dengan informasi lengkap
        $hospitalData = [
            [
                'slug' => 'rsud-sidoarjo',
                'admission_requirements' => 'Kartu Identitas / KTP (Kartu Tanda Penduduk)
Kartu BPJS (Bijak Pengguna Jaminan Sosial) (jika ada)
Kartu Pasien
Bukti Pembayaran',
                'room_prices' => 'VVIP = Rp 425.000,- Per hari*
Kelas 1 = Rp 200.000,- Per hari*
Kelas 2 = Rp 150.000,- Per hari*
Kelas 3 = Rp 100.000,- Per hari*',
                'phone_number' => '0318961649',
                'facilities' => 'Farmasi
Instalasi Gizi
Bank Darah
Ambulans
Instalasi Rawat Inap
Laboratorium
Poliklinik Umum dan Spesialis
Radiologi
Fasilitas Rehabilitasi
Ruang Kelas Edukasi dan Konseling
Ruang Gawat Darurat
Ruang Oprasi
Apotek',
                'room_prices_data' => [
                    'vvip' => 425000,
                    'class1' => 200000,
                    'class2' => 150000,
                    'class3' => 100000
                ]
            ],
            [
                'slug' => 'rsud-dr-mohammad-soewandhie',
                'admission_requirements' => 'Kartu Identitas / KTP (Kartu Tanda Penduduk)
Kartu BPJS (Bijak Pengguna Jaminan Sosial) (jika ada)
Kartu Pasien
Bukti Pembayaran',
                'room_prices' => 'VIP = Rp 900.000,- Per hari*
Kelas 1 = Rp 248.000,- Per hari*
Kelas 2 = Rp 176.000,- Per hari*
Kelas 3 = Rp 115.000,- Per hari*
(* : tidak pakai BPJS)',
                'phone_number' => '0313717141',
                'facilities' => 'Instalasi Gizi
Unit Perawatan Intensif
Instalasi Laboratorium
Farmasi
Neonate Intensive Care Unit (NICU)
Poliklinik Umum dan Spesialis
Ruang Gawat Darurat
Radiologi
Fasilitas Rehabilitasi
Ruang Oprasi
Apotek',
                'room_prices_data' => [
                    'vvip' => 900000,  // VIP = VVIP
                    'class1' => 248000,
                    'class2' => 176000,
                    'class3' => 115000
                ]
            ],
            [
                'slug' => 'rsud-dr-wahidin-sudiro-husodo',
                'admission_requirements' => 'Kartu Identitas / KTP (Kartu Tanda Penduduk)
Kartu BPJS (Bijak Pengguna Jaminan Sosial) (jika ada)
Kartu Pasien
Bukti Pembayaran',
                'room_prices' => 'VVIP = Rp 800.000,- Per hari*
Kelas 1 = Rp 225.000,- Per hari*
Kelas 2 = Rp 195.000,- Per hari*
Kelas 3 = Rp 135.000,- Per hari*
(* : tidak pakai BPJS)',
                'phone_number' => '0321322194',
                'facilities' => 'Neonate Intensive Care Unit (NICU)
Instalasi Rawat Inap
Instalasi Rawat Jalan
Instalasi Gawat Darurat (IGD)
Farmasi
Poliklinik Umum dan Spesialis
Laboratorium
Radiologi
Fasilitas Rehabilitasi
Ruang Oprasi
Apotek',
                'room_prices_data' => [
                    'vvip' => 800000,
                    'class1' => 225000,
                    'class2' => 195000,
                    'class3' => 135000
                ]
            ],
            [
                'slug' => 'rumahsakit-islam-surabaya',
                'admission_requirements' => 'Kartu Identitas / KTP (Kartu Tanda Penduduk)
Kartu BPJS (jika ada)
Kartu Pasien RSIS
Bukti Pembayaran atau Jaminan dari Penjamin',
                'room_prices' => 'VVIP = Rp 750.000,- Per hari*
Kelas 1 = Rp 235.000,- Per hari*
Kelas 2 = Rp 167.000,- Per hari*
Kelas 3 = Rp 115.000,- Per hari*
(* : tidak pakai BPJS)',
                'phone_number' => '(031) 8284505',
                'facilities' => 'Instalasi Rawat Inap dan Rawat Jalan
Instalasi Gawat Darurat (IGD) 24 jam
Poliklinik Spesialis & Subspesialis
Laboratorium & Radiologi
ICU, NICU, PICU, HCU, Perinatologi
Fasilitas Rehabilitasi Medik
Ruang Operasi & Kamar Bersalin
Farmasi & Apotek',
                'room_prices_data' => [
                    'vvip' => 750000,
                    'class1' => 235000,
                    'class2' => 167000,
                    'class3' => 115000
                ]
            ],
            [
                'slug' => 'mayapada-hospital-surabaya',
                'admission_requirements' => 'Kartu Identitas / KTP (Kartu Tanda Penduduk)
Kartu BPJS (jika ada)
Kartu Pasien
Bukti Pembayaran atau Surat Jaminan Penjamin',
                'room_prices' => 'VVIP / Presidential Suite = Rp 1.345.000,- Per hari*
Kelas 1 = Rp 750.000,- Per hari*
Kelas 2 = Rp 530.000,- Per hari*
Kelas 3 = Rp 267.000,- Per hari*
(* : tidak pakai BPJS)',
                'phone_number' => '(031) 7300 999',
                'facilities' => 'Instalasi Rawat Inap
Instalasi Rawat Jalan
Instalasi Gawat Darurat (IGD) 24 Jam
Laboratorium Klinik (BSL-2)
Radiologi (CT-Scan 128 slice, MRI 1.5T)
Endoskopi & Kateterisasi Jantung
Operating Theater & Hybrid Operating Room
ICU, HCU, NICU, PICU, Perinatologi
Ruang Isolasi
Farmasi & Apotek
Medical Check Up Center',
                'room_prices_data' => [
                    'vvip' => 1345000,
                    'class1' => 750000,
                    'class2' => 530000,
                    'class3' => 267000
                ]
            ]
        ];

        // Update data hospital
        foreach ($hospitalData as $data) {
            $hospital = Hospital::where('slug', $data['slug'])->first();
            
            if ($hospital) {
                // Update hospital data
                $hospital->update([
                    'admission_requirements' => $data['admission_requirements'],
                    'room_prices' => $data['room_prices'],
                    'phone_number' => $data['phone_number'],
                    'facilities' => $data['facilities']
                ]);

                $this->command->info("✅ Updated: {$hospital->name}");

                // Update room prices in hospital_room_types table
                foreach ($data['room_prices_data'] as $roomCode => $price) {
                    $roomType = RoomType::where('code', $roomCode)->first();
                    
                    if ($roomType) {
                        $hospitalRoomType = HospitalRoomType::where('hospital_id', $hospital->id)
                            ->where('room_type_id', $roomType->id)
                            ->first();
                        
                        if ($hospitalRoomType) {
                            $hospitalRoomType->update(['price_per_day' => $price]);
                            $this->command->info("  - Updated {$roomType->name}: Rp " . number_format($price) . "/day");
                        }
                    }
                }
            } else {
                $this->command->warn("⚠️  Hospital not found: {$data['slug']}");
            }
        }

        $this->command->info('🎉 Hospital data update completed!');
    }
}