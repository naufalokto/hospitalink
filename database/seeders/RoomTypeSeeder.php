<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            ['code' => 'vvip', 'name' => 'VVIP ROOM'],
            ['code' => 'class1', 'name' => 'CLASS 1 ROOM'],
            ['code' => 'class2', 'name' => 'CLASS 2 ROOM'],
            ['code' => 'class3', 'name' => 'CLASS 3 ROOM'],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::updateOrCreate(
                ['code' => $roomType['code']],
                $roomType
            );
        }
    }
}