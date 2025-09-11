<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\OptimizedHospitalController;
use App\Http\Controllers\OptimizedBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Web session user route
Route::middleware('web')->get('/user', function (Request $request) {
    if (auth()->check()) {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ]);
    }
    return response()->json([
        'success' => false,
        'message' => 'Not authenticated'
    ], 401);
});

// Authentication Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



// Public API Routes (for patient side)
Route::get('/hospitals/rooms', [AdminController::class, 'getPublicRoomData']);
Route::get('/hospital/{hospitalId}/room-prices', [HospitalController::class, 'getRoomPrices']);

// Optimized API Routes with caching
Route::get('/optimized/hospitals', [OptimizedHospitalController::class, 'getHospitalsWithCache']);
Route::get('/optimized/hospitals/{hospital_id}/rooms', [OptimizedHospitalController::class, 'getRoomAvailabilityWithCache']);
Route::get('/optimized/room-types', [OptimizedHospitalController::class, 'getRoomTypesWithCache']);
Route::get('/optimized/hospital-stats', [OptimizedHospitalController::class, 'getHospitalStatsWithCache']);
Route::get('/optimized/popular-hospitals', [OptimizedBookingController::class, 'getPopularHospitalsWithCache']);

// Cache management routes
Route::post('/optimized/hospitals/{hospital_id}/clear-cache', [OptimizedHospitalController::class, 'clearHospitalCache']);
Route::post('/optimized/hospitals/clear-all-cache', [OptimizedHospitalController::class, 'clearAllHospitalCache']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Optimized user booking routes with caching
    Route::get('/optimized/user/bookings', [OptimizedBookingController::class, 'getUserBookingsWithCache']);
    Route::get('/optimized/user/transactions', [OptimizedBookingController::class, 'getUserTransactionsWithCache']);
    Route::get('/optimized/user/booking-stats', [OptimizedBookingController::class, 'getBookingStatsWithCache']);
    Route::post('/optimized/user/clear-booking-cache', [OptimizedBookingController::class, 'clearUserBookingCache']);
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/hospitals', [AdminController::class, 'getHospitals']);
        Route::post('/admin/hospitals/{hospital}/rooms/update', [AdminController::class, 'updateRoomQuantities']);
        Route::post('/admin/hospitals/{hospital}/rooms/update-all', [AdminController::class, 'updateAllRoomQuantities']);
        
        // Optimized admin routes
        Route::get('/optimized/admin/hospital/{hospital_id}/booking-stats', [OptimizedBookingController::class, 'getHospitalBookingStatsWithCache']);
        Route::post('/optimized/admin/hospital/{hospital_id}/clear-booking-cache', [OptimizedBookingController::class, 'clearHospitalBookingCache']);
    });
});
