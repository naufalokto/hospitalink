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
        $hospitals = Hospital::with('room')->get();
        
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
        $hospitals = Hospital::with('room')->get();
        
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

        $hospital = Hospital::findOrFail($request->hospital_id);
        $room = $hospital->room;

        if (!$room) {
            // Create room data if it doesn't exist
            $room = $hospital->room()->create([
                'vvip_rooms' => 10,
                'class1_rooms' => 10,
                'class2_rooms' => 10,
                'class3_rooms' => 10,
            ]);
        }

        // Update the specific room type
        $roomType = $request->room_type . '_rooms';
        $room->update([
            $roomType => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room quantities updated successfully',
            'data' => [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'room_type' => $request->room_type,
                'quantity' => $request->quantity,
                'updated_rooms' => $room->room_data
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

        $hospital = Hospital::findOrFail($request->hospital_id);
        $room = $hospital->room;

        if (!$room) {
            $room = $hospital->room()->create($request->rooms);
        } else {
            $room->update([
                'vvip_rooms' => $request->rooms['vvip'],
                'class1_rooms' => $request->rooms['class1'],
                'class2_rooms' => $request->rooms['class2'],
                'class3_rooms' => $request->rooms['class3'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All room quantities updated successfully',
            'data' => [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'rooms' => $room->room_data
            ]
        ]);
    }

    /**
     * Get room data for patient side (public API)
     */
    public function getPublicRoomData(): JsonResponse
    {
        $hospitals = Hospital::with('room')->get();
        
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
                'total_rooms' => $hospital->room ? $hospital->room->total_rooms : 0,
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

            $hospital = Hospital::findOrFail($request->hospital_id);
            $room = $hospital->room;

            if (!$room) {
                $room = $hospital->room()->create([
                    'vvip_rooms' => 10,
                    'class1_rooms' => 10,
                    'class2_rooms' => 10,
                    'class3_rooms' => 10,
                ]);
            }

            $roomType = $request->room_type . '_rooms';
            $room->update([
                $roomType => $request->quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room quantities updated successfully',
                'data' => [
                    'hospital_id' => $hospital->id,
                    'hospital_name' => $hospital->name,
                    'room_type' => $request->room_type,
                    'quantity' => $request->quantity,
                    'updated_rooms' => $room->room_data
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

            $hospital = Hospital::findOrFail($request->hospital_id);
            $room = $hospital->room;

            if (!$room) {
                $room = $hospital->room()->create([
                    'vvip_rooms' => $validatedRooms['vvip'],
                    'class1_rooms' => $validatedRooms['class1'],
                    'class2_rooms' => $validatedRooms['class2'],
                    'class3_rooms' => $validatedRooms['class3'],
                ]);
            } else {
                $room->update([
                    'vvip_rooms' => $validatedRooms['vvip'],
                    'class1_rooms' => $validatedRooms['class1'],
                    'class2_rooms' => $validatedRooms['class2'],
                    'class3_rooms' => $validatedRooms['class3'],
                ]);
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
