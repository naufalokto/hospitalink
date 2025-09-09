<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\HospitalRoom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with hospital data
     */
    public function dashboard()
    {
        $hospitals = Hospital::with('roomTypes.roomType')->get();
        
        $hospitalsData = $hospitals->map(function ($hospital) {
            return [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'slug' => $hospital->slug,
                'rooms' => $hospital->room_data,
            ];
        });

        return view('admin-dashboard', compact('hospitalsData'));
    }

    /**
     * Get all hospitals with their room data
     */
    public function getHospitals(): JsonResponse
    {
        $hospitals = Hospital::with('roomTypes.roomType')->get();
        
        $data = $hospitals->map(function ($hospital) {
            return [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'slug' => $hospital->slug,
                'rooms' => $hospital->room_data,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update room quantities for a specific hospital
     */
    public function updateRoomQuantities(Request $request): JsonResponse
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'room_type' => 'required|in:vvip,class1,class2,class3',
            'quantity' => 'required|integer|min:0|max:999',
        ]);

        $hospital = Hospital::with('roomTypes.roomType')->findOrFail($request->hospital_id);
        
        // Get room type from database
        $roomTypeModel = \App\Models\RoomType::where('code', $request->room_type)->first();
        if (!$roomTypeModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid room type'
            ], 422);
        }

        // Update or create hospital room type
        $hospitalRoomType = $hospital->roomTypes()->where('room_type_id', $roomTypeModel->id)->first();
        if (!$hospitalRoomType) {
            $hospitalRoomType = $hospital->roomTypes()->create([
                'room_type_id' => $roomTypeModel->id,
                'rooms_count' => $request->quantity,
                'price_per_day' => 0, // Will be set later
            ]);
        } else {
            $hospitalRoomType->update([
                'rooms_count' => $request->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Room quantities updated successfully',
            'data' => [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'room_type' => $request->room_type,
                'quantity' => $request->quantity,
                'updated_rooms' => $hospital->room_data
            ]
        ]);
    }

    /**
     * Update all room quantities for a hospital at once
     */
    public function updateAllRoomQuantities(Request $request): JsonResponse
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'rooms' => 'required|array',
            'rooms.vvip' => 'required|integer|min:0|max:999',
            'rooms.class1' => 'required|integer|min:0|max:999',
            'rooms.class2' => 'required|integer|min:0|max:999',
            'rooms.class3' => 'required|integer|min:0|max:999',
        ]);

        $hospital = Hospital::with('roomTypes.roomType')->findOrFail($request->hospital_id);
        
        // Get all room types
        $roomTypes = \App\Models\RoomType::all();
        
        foreach ($roomTypes as $roomType) {
            $hospitalRoomType = $hospital->roomTypes()->where('room_type_id', $roomType->id)->first();
            $quantity = $request->rooms[$roomType->code] ?? 0;
            
            if (!$hospitalRoomType) {
                $hospital->roomTypes()->create([
                    'room_type_id' => $roomType->id,
                    'rooms_count' => $quantity,
                    'price_per_day' => 0, // Will be set later
                ]);
            } else {
                $hospitalRoomType->update([
                    'rooms_count' => $quantity
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'All room quantities updated successfully',
            'data' => [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'rooms' => $hospital->room_data
            ]
        ]);
    }

    /**
     * Get room data for patient side (public API)
     */
    public function getPublicRoomData(): JsonResponse
    {
        $hospitals = Hospital::with('roomTypes.roomType')->get();
        
        $data = $hospitals->map(function ($hospital) {
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

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Update room quantities for web requests
     */
    public function updateRoomQuantityWeb(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'hospital_id' => 'required|exists:hospitals,id',
                'room_type' => 'required|in:vvip,class1,class2,class3',
                'quantity' => 'required|integer|min:0|max:999',
            ]);

            $hospital = Hospital::with('roomTypes.roomType')->findOrFail($request->hospital_id);
            
            // Get room type from database
            $roomTypeModel = \App\Models\RoomType::where('code', $request->room_type)->first();
            if (!$roomTypeModel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid room type'
                ], 422);
            }

            // Update or create hospital room type
            $hospitalRoomType = $hospital->roomTypes()->where('room_type_id', $roomTypeModel->id)->first();
            if (!$hospitalRoomType) {
                $hospitalRoomType = $hospital->roomTypes()->create([
                    'room_type_id' => $roomTypeModel->id,
                    'rooms_count' => $request->quantity,
                    'price_per_day' => 0, // Will be set later
                ]);
            } else {
                $hospitalRoomType->update([
                    'rooms_count' => $request->quantity
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room quantities updated successfully',
                'data' => [
                    'hospital_id' => $hospital->id,
                    'hospital_name' => $hospital->name,
                    'room_type' => $request->room_type,
                    'quantity' => $request->quantity,
                    'updated_rooms' => $hospital->room_data
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room quantity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update all room quantities for web requests
     */
    public function updateAllRoomQuantitiesWeb(Request $request)
    {
        try {
            // Validate basic request
            $request->validate([
                'hospital_id' => 'required|exists:hospitals,id',
                'rooms' => 'required|string',
            ]);

            // Decode JSON string to array
            $rooms = json_decode($request->rooms, true);
            
            // Validate decoded rooms data
            if (!is_array($rooms)) {
                throw new \Exception('Invalid rooms data format');
            }
            
            // Validate each room type
            $validatedRooms = [];
            $roomTypes = ['vvip', 'class1', 'class2', 'class3'];
            
            foreach ($roomTypes as $type) {
                if (!isset($rooms[$type]) || !is_numeric($rooms[$type])) {
                    throw new \Exception("Invalid or missing {$type} room quantity");
                }
                
                $quantity = (int) $rooms[$type];
                if ($quantity < 0 || $quantity > 999) {
                    throw new \Exception("{$type} room quantity must be between 0 and 999");
                }
                
                $validatedRooms[$type] = $quantity;
            }

            $hospital = Hospital::with('roomTypes.roomType')->findOrFail($request->hospital_id);
            
            // Get all room types
            $roomTypes = \App\Models\RoomType::all();
            
            foreach ($roomTypes as $roomType) {
                $hospitalRoomType = $hospital->roomTypes()->where('room_type_id', $roomType->id)->first();
                $quantity = $validatedRooms[$roomType->code] ?? 0;
                
                if (!$hospitalRoomType) {
                    $hospital->roomTypes()->create([
                        'room_type_id' => $roomType->id,
                        'rooms_count' => $quantity,
                        'price_per_day' => 0, // Will be set later
                    ]);
                } else {
                    $hospitalRoomType->update([
                        'rooms_count' => $quantity
                    ]);
                }
            }

            return redirect()->route('admin-dashboard')->with('success', $hospital->name . ' updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            return redirect()->route('admin-dashboard')->with('error', 'Validation failed: ' . implode(', ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            return redirect()->route('admin-dashboard')->with('error', 'Failed to update hospital: ' . $e->getMessage());
        }
    }
}
