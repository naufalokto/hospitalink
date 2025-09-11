<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function checking($hospital_id)
    {
        // Optimize query with eager loading and fallback for numeric ID
        $hospital = Hospital::where('slug', $hospital_id)
            ->orWhere('id', $hospital_id)
            ->with(['roomTypes.roomType'])
            ->first();
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        // Get room data directly from database (no booking logic)
        $roomData = $hospital->room_data;
        
        // Create room types array from database data dynamically
        $roomTypes = [];
        $roomTypeMap = [
            'vvip' => ['id' => 1, 'location' => 'Gedung Utama Lt. 3'],
            'class1' => ['id' => 2, 'location' => 'Gedung Teratai Lt. 1 & 2'],
            'class2' => ['id' => 3, 'location' => 'Gedung MMP Lt. 1 & 2'],
            'class3' => ['id' => 4, 'location' => 'Gedung Tulip Lt. 2 & 3']
        ];

        foreach ($hospital->roomTypes as $hospitalRoomType) {
            $roomType = $hospitalRoomType->roomType;
            $code = $roomType->code;
            $availableCount = $roomData[$code] ?? 0;
            
            if (isset($roomTypeMap[$code])) {
                $roomTypes[] = [
                    'id' => $roomTypeMap[$code]['id'],
                    'name' => strtoupper($roomType->name),
                    'location' => $roomTypeMap[$code]['location'],
                    'available' => $availableCount > 0 ? $availableCount . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                    'status' => $availableCount > 0 ? 'Available' : 'Not Available',
                    'image' => 'images/rooms/' . $code . '-room.jpg',
                    'room_type_id' => $roomType->id,
                    'price_per_day' => $hospitalRoomType->price_per_day,
                    'available_count' => $availableCount
                ];
            }
        }

        return view('checking', [
            'hospital_id' => $hospital_id,
            'hospital' => $hospital,
            'roomTypes' => $roomTypes
        ]);
    }

    public function checkingDetail($hospital_id, $room_id)
    {
        // Optimize query with eager loading and fallback for numeric ID
        $hospital = Hospital::where('slug', $hospital_id)
            ->orWhere('id', $hospital_id)
            ->with(['roomTypes.roomType'])
            ->first();
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        // Get room data directly from database (no booking logic)
        $roomData = $hospital->room_data;
        
        // Define room types with their details dynamically
        $roomTypeMap = [
            1 => [
                'code' => 'vvip',
                'location' => 'Gedung Utama Lt. 3',
                'description' => 'Kamar VVIP di ' . $hospital->name . ' adalah ruang perawatan ekslusif yang dirancang untuk memberikan pengalaman menginap yang nyaman dan mewah. Ruangan ini telah memenuhi perlatan khusus, dengan fasilitas modern dan nyaman. Kamar VVIP menyediakan lingkungan yang tenang dan pribadi untuk pasien serta keluarga. Staff medis yang terlatih dengan baik dan memberikan perawatan yang personal dan terbaik karena hanya ada {available_count} kamar yang tersedia. ' . $hospital->name . ' selalu pilihan utama bagi mereka yang menginginkan layanan informasi terbaik.',
                'facilities' => [
                    '1 Bed Pasien',
                    '1 Ruang Keluarga/Pribadi',
                    '1 Lemari ES',
                    '1 Kamar mandi',
                    'Wastafel',
                    'AC',
                    'TV'
                ]
            ],
            2 => [
                'code' => 'class1',
                'location' => 'Gedung Teratai Lt. 1 & 2',
                'description' => 'Kamar kelas 1 di ' . $hospital->name . ' menawarkan fasilitas perawatan yang berkualitas tinggi bagi pasien-pasien yang membutuhkan. Ruangan ini dirancang untuk memberikan kenyamanan dan perawatan yang optimal dengan sentuhan profesionalisme. Setiap kamar dilengkapi dengan tempat tidur yang nyaman, ruang penyimpanan yang memadai, dan fasilitas kamar mandi yang bersih. Pasien dapat menikmati suasana yang tenang dan terkontrol, memungkinkan mereka untuk pulih dengan cepat. Selain itu, staf medis yang berpengalaman siap memberikan perawatan yang personal dan memperhatikan setiap kebutuhan pasien dengan cermat. Dengan kombinasi fasilitas yang memadai dan perhatian yang hangat dari tim medis, kamar kelas 1 ' . $hospital->name . ' memberikan pengalaman perawatan yang memuaskan bagi setiap pasien.',
                'facilities' => [
                    '4 Bed Pasien',
                    '4 Meja',
                    '4 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ]
            ],
            3 => [
                'code' => 'class2',
                'location' => 'Gedung MMP Lt. 1 & 2',
                'description' => 'Kamar kelas 2 di ' . $hospital->name . ' menyediakan fasilitas perawatan yang nyaman dan terjangkau. Ruangan ini dirancang untuk memberikan kenyamanan yang optimal bagi pasien dengan fasilitas yang memadai. Setiap kamar dilengkapi dengan tempat tidur yang nyaman dan fasilitas dasar yang diperlukan untuk perawatan pasien. Staf medis yang berpengalaman siap memberikan perawatan yang berkualitas dengan perhatian yang baik terhadap kebutuhan pasien.',
                'facilities' => [
                    '6 Bed Pasien',
                    '6 Meja',
                    '6 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ]
            ],
            4 => [
                'code' => 'class3',
                'location' => 'Gedung Tulip Lt. 2 & 3',
                'description' => 'Kamar kelas 3 di ' . $hospital->name . ' menawarkan fasilitas perawatan yang terjangkau dengan kualitas yang baik. Ruangan ini dirancang untuk memberikan perawatan yang nyaman bagi pasien dengan fasilitas dasar yang diperlukan. Setiap kamar dilengkapi dengan tempat tidur yang nyaman dan fasilitas yang memadai untuk perawatan pasien. Staf medis yang berpengalaman siap memberikan perawatan yang berkualitas dengan perhatian yang baik.',
                'facilities' => [
                    '8 Bed Pasien',
                    '8 Meja',
                    '8 Kursi',
                    '1 AC',
                    '1 Kamar mandi',
                    '1 TV'
                ]
            ]
        ];

        // Get the specific room type
        if (!isset($roomTypeMap[$room_id])) {
            abort(404, 'Room type not found');
        }

        $roomTypeInfo = $roomTypeMap[$room_id];
        $code = $roomTypeInfo['code'];
        $availableCount = $roomData[$code] ?? 0;

        // Find the hospital room type for this specific room
        $hospitalRoomType = $hospital->roomTypes()
            ->whereHas('roomType', function($query) use ($code) {
                $query->where('code', $code);
            })
            ->first();

        if (!$hospitalRoomType) {
            abort(404, 'Room type not found for this hospital');
        }

        $roomType = $hospitalRoomType->roomType;

        $room = [
            'id' => $room_id,
            'name' => strtoupper($roomType->name),
            'location' => $roomTypeInfo['location'],
            'available' => $availableCount > 0 ? $availableCount . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
            'status' => $availableCount > 0 ? 'Available' : 'Not Available',
            'image' => 'images/rooms/' . $code . '-room.jpg',
            'description' => str_replace('{available_count}', $availableCount, $roomTypeInfo['description']),
            'facilities' => $roomTypeInfo['facilities'],
            'price' => 'Rp ' . number_format($hospitalRoomType->price_per_day, 0, ',', '.') . ',- Per hari',
            'room_type_id' => $roomType->id,
            'price_per_day' => $hospitalRoomType->price_per_day,
            'available_count' => $availableCount
        ];

        return view('checking-detail', [
            'hospital' => $hospital,
            'room' => $room
        ]);
    }

    /**
     * Get real-time room availability data for a hospital
     */
    public function getRoomAvailability($hospital_id)
    {
        try {
            // Optimize query with eager loading and fallback for numeric ID
            $hospital = Hospital::where('slug', $hospital_id)
                ->orWhere('id', $hospital_id)
                ->with(['roomTypes.roomType'])
                ->first();
            
            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            // Get room data directly from database (no booking logic)
            $roomData = $hospital->room_data;
            
            // Create room types array from database data dynamically
            $roomTypes = [];
            $roomTypeMap = [
                'vvip' => ['id' => 1, 'location' => 'Gedung Utama Lt. 3'],
                'class1' => ['id' => 2, 'location' => 'Gedung Teratai Lt. 1 & 2'],
                'class2' => ['id' => 3, 'location' => 'Gedung MMP Lt. 1 & 2'],
                'class3' => ['id' => 4, 'location' => 'Gedung Tulip Lt. 2 & 3']
            ];

            foreach ($hospital->roomTypes as $hospitalRoomType) {
                $roomType = $hospitalRoomType->roomType;
                $code = $roomType->code;
                $availableCount = $roomData[$code] ?? 0;
                
                if (isset($roomTypeMap[$code])) {
                    $roomTypes[] = [
                        'id' => $roomTypeMap[$code]['id'],
                        'name' => strtoupper($roomType->name),
                        'location' => $roomTypeMap[$code]['location'],
                        'available' => $availableCount > 0 ? $availableCount . ' ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                        'status' => $availableCount > 0 ? 'Available' : 'Not Available',
                        'image' => 'images/rooms/' . $code . '-room.jpg',
                        'room_type_id' => $roomType->id,
                        'price_per_day' => $hospitalRoomType->price_per_day,
                        'available_count' => $availableCount,
                        'code' => $code
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'hospital' => [
                        'id' => $hospital->id,
                        'name' => $hospital->name,
                        'slug' => $hospital->slug,
                        'address' => $hospital->address,
                        'image_url' => $hospital->image_url
                    ],
                    'room_types' => $roomTypes,
                    'last_updated' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get room availability: ' . $e->getMessage()
            ], 500);
        }
    }
}
