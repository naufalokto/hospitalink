<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class OptimizedBookingController extends Controller
{
    /**
     * Get user bookings with caching and pagination
     */
    public function getUserBookingsWithCache(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');
        
        $cacheKey = "user_bookings_{$userId}_page_{$page}_per_{$perPage}_status_{$status}";
        
        // Cache for 5 minutes
        $bookings = Cache::remember($cacheKey, 300, function () use ($userId, $perPage, $status) {
            $query = Booking::with(['hospital', 'roomType', 'payments'])
                ->where('user_id', $userId);
            
            if ($status) {
                $query->where('status', $status);
            }
            
            return $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
        });

        return response()->json([
            'success' => true,
            'data' => $bookings,
            'cached' => true
        ]);
    }

    /**
     * Get user transaction history with caching
     */
    public function getUserTransactionsWithCache(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status', 'completed');
        
        $cacheKey = "user_transactions_{$userId}_page_{$page}_per_{$perPage}_status_{$status}";
        
        // Cache for 10 minutes
        $transactions = Cache::remember($cacheKey, 600, function () use ($userId, $perPage, $status) {
            return TransactionDetail::with(['booking', 'hospital', 'roomType', 'payment'])
                ->where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('payment_completed_at', 'desc')
                ->paginate($perPage);
        });

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'cached' => true
        ]);
    }

    /**
     * Get booking statistics with caching
     */
    public function getBookingStatsWithCache(): JsonResponse
    {
        $userId = Auth::id();
        $cacheKey = "booking_stats_{$userId}";
        
        // Cache for 15 minutes
        $stats = Cache::remember($cacheKey, 900, function () use ($userId) {
            $totalBookings = Booking::where('user_id', $userId)->count();
            $pendingBookings = Booking::where('user_id', $userId)->where('status', 'pending')->count();
            $confirmedBookings = Booking::where('user_id', $userId)->where('status', 'confirmed')->count();
            $cancelledBookings = Booking::where('user_id', $userId)->where('status', 'cancelled')->count();
            
            $totalSpent = TransactionDetail::where('user_id', $userId)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $recentBookings = Booking::where('user_id', $userId)
                ->with(['hospital', 'roomType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return [
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'confirmed_bookings' => $confirmedBookings,
                'cancelled_bookings' => $cancelledBookings,
                'total_spent' => $totalSpent,
                'recent_bookings' => $recentBookings,
                'last_updated' => now()->toISOString()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'cached' => true
        ]);
    }

    /**
     * Get hospital booking statistics with caching
     */
    public function getHospitalBookingStatsWithCache($hospital_id): JsonResponse
    {
        $cacheKey = "hospital_booking_stats_{$hospital_id}";
        
        // Cache for 10 minutes
        $stats = Cache::remember($cacheKey, 600, function () use ($hospital_id) {
            $totalBookings = Booking::where('hospital_id', $hospital_id)->count();
            $pendingBookings = Booking::where('hospital_id', $hospital_id)->where('status', 'pending')->count();
            $confirmedBookings = Booking::where('hospital_id', $hospital_id)->where('status', 'confirmed')->count();
            $cancelledBookings = Booking::where('hospital_id', $hospital_id)->where('status', 'cancelled')->count();
            
            $totalRevenue = TransactionDetail::where('hospital_id', $hospital_id)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $roomTypeStats = Booking::where('hospital_id', $hospital_id)
                ->where('status', 'confirmed')
                ->selectRaw('room_type, COUNT(*) as count')
                ->groupBy('room_type')
                ->get()
                ->pluck('count', 'room_type');

            return [
                'hospital_id' => $hospital_id,
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'confirmed_bookings' => $confirmedBookings,
                'cancelled_bookings' => $cancelledBookings,
                'total_revenue' => $totalRevenue,
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

    /**
     * Clear user booking cache
     */
    public function clearUserBookingCache(): JsonResponse
    {
        $userId = Auth::id();
        
        // Clear user-specific caches
        Cache::forget("booking_stats_{$userId}");
        
        // Clear paginated caches (this is a simplified approach)
        // In production, you might want to use cache tags
        for ($page = 1; $page <= 10; $page++) {
            for ($perPage = 10; $perPage <= 50; $perPage += 10) {
                Cache::forget("user_bookings_{$userId}_page_{$page}_per_{$perPage}_status_");
                Cache::forget("user_bookings_{$userId}_page_{$page}_per_{$perPage}_status_pending");
                Cache::forget("user_bookings_{$userId}_page_{$page}_per_{$perPage}_status_confirmed");
                Cache::forget("user_transactions_{$userId}_page_{$page}_per_{$perPage}_status_completed");
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User booking cache cleared successfully'
        ]);
    }

    /**
     * Clear hospital booking cache
     */
    public function clearHospitalBookingCache($hospital_id): JsonResponse
    {
        Cache::forget("hospital_booking_stats_{$hospital_id}");

        return response()->json([
            'success' => true,
            'message' => 'Hospital booking cache cleared successfully'
        ]);
    }

    /**
     * Get popular hospitals with caching
     */
    public function getPopularHospitalsWithCache(): JsonResponse
    {
        $cacheKey = 'popular_hospitals';
        
        // Cache for 1 hour
        $hospitals = Cache::remember($cacheKey, 3600, function () {
            return \App\Models\Hospital::with(['roomTypes.roomType'])
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($hospital) {
                    return [
                        'id' => $hospital->id,
                        'name' => $hospital->name,
                        'slug' => $hospital->slug,
                        'address' => $hospital->address,
                        'image_url' => $hospital->image_url,
                        'total_bookings' => $hospital->bookings_count,
                        'rooms' => $hospital->room_data,
                        'total_rooms' => array_sum($hospital->room_data)
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'data' => $hospitals,
            'cached' => true
        ]);
    }
}
