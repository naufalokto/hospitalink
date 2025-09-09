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
                'name' => 'Rumah Sakit Umum Daerah Malang',
                'slug' => 'rsud-malang',
                'address' => 'Jl. Semeru No.1, Oro-Oro Dowo, Kec. Klojen, Kota Malang, Jawa Timur 65119',
                'description' => 'Rumah Sakit Umum Daerah Malang - Data akan diisi oleh tim pengembang',
                'public_service' => 'Layanan kesehatan umum dan spesialis - Data akan diisi oleh tim pengembang',
                'image_url' => 'images/hospitals/rsud_malang.jpg',
                'website_url' => 'https://rsud.malangkota.go.id/',
            ],
            [
                'name' => 'Rumah Sakit Umum Daerah Kediri',
                'slug' => 'rsud-kediri',
                'address' => 'Jl. Dr. Sutomo No.1, Ngadiluwih, Kec. Ngadiluwih, Kabupaten Kediri, Jawa Timur 64171',
                'description' => 'Rumah Sakit Umum Daerah Kediri - Data akan diisi oleh tim pengembang',
                'public_service' => 'Layanan kesehatan umum dan spesialis - Data akan diisi oleh tim pengembang',
                'image_url' => 'images/hospitals/rsud_kediri.jpg',
                'website_url' => 'https://rsud.kedirikab.go.id/',
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