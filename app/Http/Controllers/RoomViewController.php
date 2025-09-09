<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class RoomViewController extends Controller
{
    public function index()
    {
        // Get all hospitals with their room data
        $hospitals = Hospital::with('roomTypes.roomType')->get();
        
        // Transform data for the view
        $hospitalsData = $hospitals->map(function ($hospital) {
            $roomData = $hospital->room_data;
            $totalRooms = array_sum($roomData);
            
            return [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'slug' => $hospital->slug,
                'address' => $hospital->address,
                'image_url' => $hospital->image_url,
                'website_url' => $hospital->website_url,
                'rooms' => $roomData,
                'total_rooms' => $totalRooms,
                'status' => $totalRooms > 0 ? 'ROOM AVAILABLE' : 'NO ROOM AVAILABLE',
                'status_class' => $totalRooms > 0 ? 'text-green-600' : 'text-red-600'
            ];
        });

        return view('room', compact('hospitalsData'));
    }
}
