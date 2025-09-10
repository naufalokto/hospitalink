<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('hospital', compact('hospitals'));
    }

    public function show($slug)
    {
        $hospital = Hospital::where('slug', $slug)->firstOrFail();
        return view('hospital-detail', compact('hospital'));
    }

    /**
     * Get room prices for a specific hospital
     */
    public function getRoomPrices($hospitalId): JsonResponse
    {
        // Find hospital by ID or slug
        $hospital = Hospital::where('id', $hospitalId)
            ->orWhere('slug', $hospitalId)
            ->with('roomTypes.roomType')
            ->first();

        if (!$hospital) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hospital not found'
            ], 404);
        }

        // Build room prices array
        $roomPrices = [];
        foreach ($hospital->roomTypes as $hospitalRoomType) {
            $roomType = $hospitalRoomType->roomType;
            $roomPrices[$roomType->code] = $hospitalRoomType->price_per_day;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'room_prices' => $roomPrices
            ]
        ]);
    }
}
