<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class OptimizedHospitalController extends Controller
{
    /**
     * Get all hospitals with caching for better performance
     */
    public function getHospitalsWithCache(): JsonResponse
    {
        // Cache hospitals data for 30 minutes
        $hospitals = Cache::remember('hospitals_with_rooms', 1800, function () {
            return Hospital::with(['roomTypes.roomType'])
                ->orderBy('name')
                ->get()
                ->map(function ($hospital) {
                    return [
                        'id' => $hospital->id,
                        'name' => $hospital->name,
                        'slug' => $hospital->slug,
                        'address' => $hospital->address,
                        'description' => $hospital->description,
                        'image_url' => $hospital->image_url,
                        'website_url' => $hospital->website_url,
                        'rooms' => $hospital->room_data,
                        'total_rooms' => array_sum($hospital->room_data),
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'data' => $hospitals,
            'cached' => true
        ]);
    }

    /**
     * Get hospital room availability with caching
     */
    public function getRoomAvailabilityWithCache($hospital_id): JsonResponse
    {
        $cacheKey = "hospital_rooms_{$hospital_id}";
        
        // Cache room availability for 5 minutes
        $data = Cache::remember($cacheKey, 300, function () use ($hospital_id) {
            $hospital = Hospital::where('slug', $hospital_id)
                ->orWhere('id', $hospital_id)
                ->with(['roomTypes.roomType'])
                ->first();
            
            if (!$hospital) {
                return null;
            }

            $roomData = $hospital->room_data;
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

            return [
                'hospital' => [
                    'id' => $hospital->id,
                    'name' => $hospital->name,
                    'slug' => $hospital->slug,
                    'address' => $hospital->address,
                    'image_url' => $hospital->image_url
                ],
                'room_types' => $roomTypes,
                'last_updated' => now()->toISOString()
            ];
        });

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'cached' => true
        ]);
    }

    /**
     * Clear cache for specific hospital
     */
    public function clearHospitalCache($hospital_id): JsonResponse
    {
        $cacheKey = "hospital_rooms_{$hospital_id}";
        Cache::forget($cacheKey);
        Cache::forget('hospitals_with_rooms');

        return response()->json([
            'success' => true,
            'message' => 'Cache cleared successfully'
        ]);
    }

    /**
     * Clear all hospital caches
     */
    public function clearAllHospitalCache(): JsonResponse
    {
        Cache::forget('hospitals_with_rooms');
        
        // Clear individual hospital caches
        $hospitals = Hospital::pluck('id');
        foreach ($hospitals as $hospitalId) {
            Cache::forget("hospital_rooms_{$hospitalId}");
        }

        return response()->json([
            'success' => true,
            'message' => 'All hospital caches cleared successfully'
        ]);
    }

    /**
     * Get room types with caching
     */
    public function getRoomTypesWithCache(): JsonResponse
    {
        $roomTypes = Cache::remember('room_types', 3600, function () {
            return \App\Models\RoomType::orderBy('name')->get();
        });

        return response()->json([
            'success' => true,
            'data' => $roomTypes,
            'cached' => true
        ]);
    }

    /**
     * Get hospital statistics with caching
     */
    public function getHospitalStatsWithCache(): JsonResponse
    {
        $stats = Cache::remember('hospital_stats', 1800, function () {
            $hospitals = Hospital::with(['roomTypes.roomType'])->get();
            
            $totalHospitals = $hospitals->count();
            $totalRooms = 0;
            $availableRooms = 0;
            $roomTypeStats = [];

            foreach ($hospitals as $hospital) {
                $roomData = $hospital->room_data;
                $totalRooms += array_sum($roomData);
                
                foreach ($roomData as $type => $count) {
                    if (!isset($roomTypeStats[$type])) {
                        $roomTypeStats[$type] = 0;
                    }
                    $roomTypeStats[$type] += $count;
                }
            }

            return [
                'total_hospitals' => $totalHospitals,
                'total_rooms' => $totalRooms,
                'room_type_breakdown' => $roomTypeStats,
                'last_updated' => now()->toISOString()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'cached' => true
        ]);
    }
}
