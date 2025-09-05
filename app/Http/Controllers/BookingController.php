<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hospital;
use App\Models\HospitalRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function showBookingForm($hospital_id, $room_id)
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
                'type' => 'vvip',
                'location' => 'Gedung Utama Lt. 3',
                'available' => $roomData['vvip'],
                'price' => 825000,
                'image' => 'images/rooms/vvip-room.jpg'
            ],
            2 => [
                'id' => 2,
                'name' => 'CLASS 1 ROOM',
                'type' => 'class1',
                'location' => 'Gedung Teratai Lt. 1 & 2',
                'available' => $roomData['class1'],
                'price' => 200000,
                'image' => 'images/rooms/class1-room.jpg'
            ],
            3 => [
                'id' => 3,
                'name' => 'CLASS 2 ROOM',
                'type' => 'class2',
                'location' => 'Gedung MMP Lt. 1 & 2',
                'available' => $roomData['class2'],
                'price' => 150000,
                'image' => 'images/rooms/class2-room.jpg'
            ],
            4 => [
                'id' => 4,
                'name' => 'CLASS 3 ROOM',
                'type' => 'class3',
                'location' => 'Gedung Tulip Lt. 2 & 3',
                'available' => $roomData['class3'],
                'price' => 100000,
                'image' => 'images/rooms/class3-room.jpg'
            ]
        ];

        // Get the specific room type
        if (!isset($roomTypes[$room_id])) {
            abort(404, 'Room type not found');
        }

        $room = $roomTypes[$room_id];

        // Check if room is available
        if ($room['available'] <= 0) {
            return redirect()->back()->with('error', 'Maaf, kamar tidak tersedia saat ini.');
        }

        return view('booking-form', [
            'hospital' => $hospital,
            'room' => $room
        ]);
    }

    public function processBooking(Request $request, $hospital_id, $room_id)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_email' => 'nullable|email|max:255',
            'patient_address' => 'nullable|string',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string'
        ]);

        // Get hospital data
        $hospital = Hospital::where('slug', $hospital_id)->with('room')->first();
        
        if (!$hospital) {
            return redirect()->back()->with('error', 'Hospital not found');
        }

        // Get actual available room data
        $roomData = $hospital->actual_room_data;
        
        // Define room types with their details
        $roomTypes = [
            1 => ['name' => 'VVIP ROOM', 'type' => 'vvip', 'price' => 825000],
            2 => ['name' => 'CLASS 1 ROOM', 'type' => 'class1', 'price' => 200000],
            3 => ['name' => 'CLASS 2 ROOM', 'type' => 'class2', 'price' => 150000],
            4 => ['name' => 'CLASS 3 ROOM', 'type' => 'class3', 'price' => 100000]
        ];

        if (!isset($roomTypes[$room_id])) {
            return redirect()->back()->with('error', 'Room type not found');
        }

        $room = $roomTypes[$room_id];

        // Check room availability
        if ($roomData[$room['type']] <= 0) {
            return redirect()->back()->with('error', 'Maaf, kamar tidak tersedia saat ini.');
        }

        // Calculate duration and total price
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $durationDays = $checkIn->diffInDays($checkOut);
        $totalPrice = $room['price'] * $durationDays;

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hospital_id' => $hospital->id,
                'room_type' => $room['type'],
                'room_name' => $room['name'],
                'patient_name' => $request->patient_name,
                'patient_phone' => $request->patient_phone,
                'patient_email' => $request->patient_email,
                'patient_address' => $request->patient_address,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'duration_days' => $durationDays,
                'price_per_day' => $room['price'],
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'notes' => $request->notes
            ]);

            // Reduce room count
            $hospitalRoom = $hospital->room;
            if ($hospitalRoom) {
                switch ($room['type']) {
                    case 'vvip':
                        $hospitalRoom->decrement('vvip_rooms');
                        break;
                    case 'class1':
                        $hospitalRoom->decrement('class1_rooms');
                        break;
                    case 'class2':
                        $hospitalRoom->decrement('class2_rooms');
                        break;
                    case 'class3':
                        $hospitalRoom->decrement('class3_rooms');
                        break;
                }
            }

            DB::commit();

            // Redirect to invoice page
            return redirect()->route('booking.invoice', $booking->id)
                ->with('success', 'Booking berhasil! Silakan lihat invoice Anda.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses booking. Silakan coba lagi.');
        }
    }

    public function showInvoice($booking_id)
    {
        $booking = Booking::with(['hospital', 'user'])->findOrFail($booking_id);

        // Check if user owns this booking or is admin
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        return view('booking-invoice', [
            'booking' => $booking
        ]);
    }

    public function downloadInvoice($booking_id)
    {
        $booking = Booking::with(['hospital', 'user'])->findOrFail($booking_id);

        // Check if user owns this booking or is admin
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        // Generate PDF invoice (you can implement PDF generation here)
        // For now, we'll just return the invoice view
        return view('booking-invoice', [
            'booking' => $booking,
            'download' => true
        ]);
    }

    public function myBookings()
    {
        $bookings = Booking::with('hospital')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('my-bookings', [
            'bookings' => $bookings
        ]);
    }
}