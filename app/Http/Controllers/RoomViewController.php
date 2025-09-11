<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class RoomViewController extends Controller
{
    public function index(Request $request)
    {
        // Get selected service from query parameter
        $selectedService = $request->query('service', 'ALL');
        
        // Define recommended hospital slugs for each service
        $recommendedHospitals = [
            'IGD 24 Jam' => ['rsud-sidoarjo', 'rumahsakit-islam-surabaya', 'mayapada-hospital-surabaya'],
            'Poliklinik' => ['rsud-husodo', 'rsud-sidoarjo', 'mayapada-hospital-surabaya'],
            'Farmasi 24 Jam' => ['mayapada-hospital-surabaya', 'rumahsakit-islam-surabaya']
        ];
        
        // Optimize query with eager loading and conditional filtering
        $query = Hospital::with(['roomTypes.roomType']);
        
        // Filter hospitals if a specific service is selected
        if ($selectedService !== 'all' && isset($recommendedHospitals[$selectedService])) {
            $query->whereIn('slug', $recommendedHospitals[$selectedService]);
        }
        
        // Execute optimized query
        $hospitals = $query->get();
        
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
