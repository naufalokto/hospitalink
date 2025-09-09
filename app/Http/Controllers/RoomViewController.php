<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class RoomViewController extends Controller
{
    public function index(Request $request)
    {
        // Get all hospitals with their room data
        $hospitals = Hospital::with('roomTypes.roomType')->get();
        
        // Get selected service from query parameter
        $selectedService = $request->query('service', 'ALL');
        
        // Define recommended hospital slugs for each service
        $recommendedHospitals = [
            'IGD 24 Jam' => ['rsud-sidoarjo', 'rumahsakit-islam-surabaya', 'mayapada-hospital-surabaya'],
            'Poliklinik' => ['rsud-husodo', 'rsud-sidoarjo', 'mayapada-hospital-surabaya'],
            'Farmasi 24 Jam' => ['mayapada-hospital-surabaya', 'rumahsakit-islam-surabaya']
        ];
        
        // Filter hospitals if a specific service is selected
        if ($selectedService !== 'all' && isset($recommendedHospitals[$selectedService])) {
            $hospitals = $hospitals->filter(function($hospital) use ($recommendedHospitals, $selectedService) {
                return in_array($hospital->slug, $recommendedHospitals[$selectedService]);
            });
        }
        
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
