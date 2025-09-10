<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\RoomType;
use App\Models\HospitalRoomType;

class UpdateHospitalRoomPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏥 Updating hospital room prices...');

        // Get all room types
        $roomTypes = RoomType::all()->keyBy('code');
        
        if ($roomTypes->isEmpty()) {
            $this->command->error('No room types found. Please run RoomTypeSeeder first.');
            return;
        }

        // Define hospital pricing data based on the provided information
        $hospitalPricing = [
            // RSUD Sidoarjo
            'rsud-sidoarjo' => [
                'vvip' => 425000,
                'class1' => 200000,
                'class2' => 150000,
                'class3' => 100000,
            ],
            
            // RSUD Dr. Mohammad Soewandhie
            'rsud-dr-mohammad-soewandhie' => [
                'vvip' => 900000, // VIP price mapped to VVIP
                'class1' => 248000,
                'class2' => 176000,
                'class3' => 115000,
            ],
            
            // RSUD Dr Wahidin Sudiro Husodo
            'rsud-dr-wahidin-sudiro-husodo' => [
                'vvip' => 800000,
                'class1' => 225000,
                'class2' => 195000,
                'class3' => 135000,
            ],
            
            // RSIS A. Yani
            'rumahsakit-islam-surabaya' => [
                'vvip' => 750000,
                'class1' => 235000,
                'class2' => 167000,
                'class3' => 115000,
            ],
            
            // Mayapada Hospital
            'mayapada-hospital-surabaya' => [
                'vvip' => 1345000, // VVIP/Presidential Suite
                'class1' => 750000,
                'class2' => 530000,
                'class3' => 267000,
            ],
        ];

        $updatedCount = 0;
        $notFoundCount = 0;

        foreach ($hospitalPricing as $hospitalSlug => $prices) {
            // Find hospital by slug
            $hospital = Hospital::where('slug', $hospitalSlug)->first();
            
            if (!$hospital) {
                $this->command->warn("❌ Hospital not found: {$hospitalSlug}");
                $notFoundCount++;
                continue;
            }

            $this->command->info("🏥 Updating prices for: {$hospital->name}");

            foreach ($prices as $roomTypeCode => $price) {
                $roomType = $roomTypes[$roomTypeCode] ?? null;
                
                if (!$roomType) {
                    $this->command->warn("  ⚠️  Room type not found: {$roomTypeCode}");
                    continue;
                }

                // Update or create hospital room type with new price
                $hospitalRoomType = HospitalRoomType::updateOrCreate(
                    [
                        'hospital_id' => $hospital->id,
                        'room_type_id' => $roomType->id,
                    ],
                    [
                        'price_per_day' => $price,
                        'rooms_count' => 10, // Default room count if not exists
                    ]
                );

                $this->command->info("  ✅ {$roomType->name}: Rp " . number_format($price) . "/day");
                $updatedCount++;
            }
        }

        $this->command->info('');
        $this->command->info("📊 Summary:");
        $this->command->info("  ✅ Updated: {$updatedCount} room prices");
        if ($notFoundCount > 0) {
            $this->command->info("  ❌ Not found: {$notFoundCount} hospitals");
        }
        $this->command->info('');
        $this->command->info('🎉 Hospital room prices updated successfully!');
    }
}
