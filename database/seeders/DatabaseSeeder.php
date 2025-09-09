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
        $this->call(NewsSeeder::class);
        $this->call(AdminUserSeeder::class);

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]
        );
    }
}
