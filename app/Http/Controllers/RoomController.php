<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function checking($hospital_id)
    {
        // Data rumah sakit (nanti bisa diambil dari database berdasarkan hospital_id)
        $hospital = [
            'id' => $hospital_id,
            'name' => 'RSUD Sidoarjo',
            'image' => 'images/hospitals/rsud_sidoarjo.jpg',
            'address' => 'Jl. Mojopahit No. 667, Sidoarjo'
        ];

        // Contoh data room types (nanti bisa diambil dari database)
        $roomTypes = [
            [
                'id' => 1,
                'name' => 'VVIP ROOM',
                'location' => 'Gedung Utama Lt. 3',
                'available' => '3 ROOM AVAILABLE',
                'status' => 'Available',
                'image' => 'images/rooms/vvip-room.jpg'
            ],
            [
                'id' => 2,
                'name' => 'CLASS 1 ROOM',
                'location' => 'Gedung Teratai Lt. 1 & 2',
                'available' => '8 ROOM AVAILABLE',
                'status' => 'Available',
                'image' => 'images/rooms/class1-room.jpg'
            ],
            [
                'id' => 3,
                'name' => 'CLASS 2 ROOM',
                'location' => 'Gedung MMP Lt. 1 & 2',
                'available' => 'NO ROOM AVAILABLE',
                'status' => 'Not Available',
                'image' => 'images/rooms/class2-room.jpg'
            ],
            [
                'id' => 4,
                'name' => 'CLASS 3 ROOM',
                'location' => 'Gedung Tulip Lt. 2 & 3',
                'available' => '15 ROOM AVAILABLE',
                'status' => 'Available',
                'image' => 'images/rooms/class3-room.jpg'
            ]
        ];

        return view('checking', [
            'hospital_id' => $hospital_id,
            'hospital' => $hospital,
            'roomTypes' => $roomTypes
        ]);
    }

    public function checkingDetail($hospital_id, $room_id)
    {
        // Data akan diambil dari database berdasarkan hospital_id dan room_id
        // Untuk sementara menggunakan data statis
        $hospital = [
            'id' => $hospital_id,
            'name' => 'RSUD Sidoarjo',
            'image' => 'images/hospitals/rsud_sidoarjo.jpg',
            'address' => 'Jl. Mojopahit No. 667, Sidoarjo'
        ];

        $room = [
            'id' => $room_id,
            'name' => 'VVIP ROOM',
            'location' => 'Gedung Utama Lt. 3',
            'available' => '3 ROOM AVAILABLE',
            'status' => 'Available',
            'image' => 'images/rooms/vvip-room.jpg',
            'facilities' => [
                'AC',
                'TV',
                'Private Bathroom',
                'Refrigerator',
                'Dining Set'
            ],
            'price' => 'Rp 1.500.000/hari'
        ];

        return view('checking-detail', [
            'hospital' => $hospital,
            'room' => $room
        ]);
    }
}
