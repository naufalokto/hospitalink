<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function checking($hospital_id)
    {
        // Get hospital data from database
        $hospital = Hospital::where('slug', $hospital_id)->with('room')->first();
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        // Get actual available room data from database (considering bookings)
        $roomData = $hospital->actual_room_data;
        
        // Create room types array from database data
        $roomTypes = [
            [
                'id' => 1,
                'name' => 'VVIP ROOM',
                'location' => 'Gedung Utama Lt. 3',
                'available' => $roomData['vvip'] > 0 ? $roomData['vvip'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['vvip'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/vvip-room.jpg'
            ],
            [
                'id' => 2,
                'name' => 'CLASS 1 ROOM',
                'location' => 'Gedung Teratai Lt. 1 & 2',
                'available' => $roomData['class1'] > 0 ? $roomData['class1'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class1'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/class1-room.jpg'
            ],
            [
                'id' => 3,
                'name' => 'CLASS 2 ROOM',
                'location' => 'Gedung MMP Lt. 1 & 2',
                'available' => $roomData['class2'] > 0 ? $roomData['class2'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class2'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/class2-room.jpg'
            ],
            [
                'id' => 4,
                'name' => 'CLASS 3 ROOM',
                'location' => 'Gedung Tulip Lt. 2 & 3',
                'available' => $roomData['class3'] > 0 ? $roomData['class3'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class3'] > 0 ? 'Available' : 'Not Available',
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
        // Get hospital data from database
        $hospital = Hospital::where('slug', $hospital_id)->with('room')->first();
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        // Get actual available room data from database (considering bookings)
        $roomData = $hospital->actual_room_data;
        
        // Define room types with their details
        $roomTypes = [
            1 => [
                'id' => 1,
            'name' => 'VVIP ROOM',
            'location' => 'Gedung Utama Lt. 3',
                'available' => $roomData['vvip'] > 0 ? $roomData['vvip'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['vvip'] > 0 ? 'Available' : 'Not Available',
            'image' => 'images/rooms/vvip-room.jpg',
                'description' => 'Kamar VVIP di ' . $hospital->name . ' adalah ruang perawatan ekslusif yang dirancang untuk memberikan pengalaman menginap yang nyaman dan mewah. Ruangan ini telah memenuhi perlatan khusus, dengan fasilitas modern dan nyaman. Kamar VVIP menyediakan lingkungan yang tenang dan pribadi untuk pasien serta keluarga. Staff medis yang terlatih dengan baik dan memberikan perawatan yang personal dan terbaik karena hanya ada ' . $roomData['vvip'] . ' kamar yang tersedia. ' . $hospital->name . ' selalu pilihan utama bagi mereka yang menginginkan layanan informasi terbaik.',
            'facilities' => [
                    '1 Bed Pasien',
                    '1 Ruang Keluarga/Pribadi',
                    '1 Lemari ES',
                    '1 Kamar mandi',
                    'Wastafel',
                'AC',
                    'TV'
                ],
                'price' => 'Rp 825.000,- Per hari'
            ],
            2 => [
                'id' => 2,
                'name' => 'CLASS 1 ROOM',
                'location' => 'Gedung Teratai Lt. 1 & 2',
                'available' => $roomData['class1'] > 0 ? $roomData['class1'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class1'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/class1-room.jpg',
                'description' => 'Kamar kelas 1 di ' . $hospital->name . ' menawarkan fasilitas perawatan yang berkualitas tinggi bagi pasien-pasien yang membutuhkan. Ruangan ini dirancang untuk memberikan kenyamanan dan perawatan yang optimal dengan sentuhan profesionalisme. Setiap kamar dilengkapi dengan tempat tidur yang nyaman, ruang penyimpanan yang memadai, dan fasilitas kamar mandi yang bersih. Pasien dapat menikmati suasana yang tenang dan terkontrol, memungkinkan mereka untuk pulih dengan cepat. Selain itu, staf medis yang berpengalaman siap memberikan perawatan yang personal dan memperhatikan setiap kebutuhan pasien dengan cermat. Dengan kombinasi fasilitas yang memadai dan perhatian yang hangat dari tim medis, kamar kelas 1 ' . $hospital->name . ' memberikan pengalaman perawatan yang memuaskan bagi setiap pasien.',
                'facilities' => [
                    '4 Bed Pasien',
                    '4 Meja',
                    '4 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ],
                'price' => 'Rp 200.000,- Per hari'
            ],
            3 => [
                'id' => 3,
                'name' => 'CLASS 2 ROOM',
                'location' => 'Gedung MMP Lt. 1 & 2',
                'available' => $roomData['class2'] > 0 ? $roomData['class2'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class2'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/class2-room.jpg',
                'description' => 'Kamar kelas 2 di ' . $hospital->name . ' menyediakan fasilitas perawatan yang nyaman dan terjangkau. Ruangan ini dirancang untuk memberikan kenyamanan yang optimal bagi pasien dengan fasilitas yang memadai. Setiap kamar dilengkapi dengan tempat tidur yang nyaman dan fasilitas dasar yang diperlukan untuk perawatan pasien. Staf medis yang berpengalaman siap memberikan perawatan yang berkualitas dengan perhatian yang baik terhadap kebutuhan pasien.',
                'facilities' => [
                    '6 Bed Pasien',
                    '6 Meja',
                    '6 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ],
                'price' => 'Rp 150.000,- Per hari'
            ],
            4 => [
                'id' => 4,
                'name' => 'CLASS 3 ROOM',
                'location' => 'Gedung Tulip Lt. 2 & 3',
                'available' => $roomData['class3'] > 0 ? $roomData['class3'] . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status' => $roomData['class3'] > 0 ? 'Available' : 'Not Available',
                'image' => 'images/rooms/class3-room.jpg',
                'description' => 'Kamar kelas 3 di ' . $hospital->name . ' menawarkan fasilitas perawatan yang terjangkau dengan kualitas yang baik. Ruangan ini dirancang untuk memberikan perawatan yang nyaman bagi pasien dengan fasilitas dasar yang diperlukan. Setiap kamar dilengkapi dengan tempat tidur yang nyaman dan fasilitas yang memadai untuk perawatan pasien. Staf medis yang berpengalaman siap memberikan perawatan yang berkualitas dengan perhatian yang baik.',
                'facilities' => [
                    '8 Bed Pasien',
                    '8 Meja',
                    '8 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ],
                'price' => 'Rp 100.000,- Per hari'
            ]
        ];

        // Get the specific room type
        if (!isset($roomTypes[$room_id])) {
            abort(404, 'Room type not found');
        }

        $room = $roomTypes[$room_id];

        return view('checking-detail', [
            'hospital' => $hospital,
            'room' => $room
        ]);
    }
}
