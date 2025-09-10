<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoomTypeSeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(HospitalSeeder::class);
        // $this->call(HospitalRoomSeeder::class); // Table hospital_rooms has been removed
        $this->call(HospitalRoomTypeSeeder::class);
        $this->call(HospitalRoomTypeFacilitySeeder::class);
        $this->call(HospitalDataSeeder::class); // Update hospital data with additional information
        $this->call(UpdateHospitalRoomPricesSeeder::class); // Update room prices with real data
        $this->call(NewsSeeder::class);
        $this->call(AdminUserSeeder::class);

        // Test user removed - system now works with real users who signup and login
    }
}
