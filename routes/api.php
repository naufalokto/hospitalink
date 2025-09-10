<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HospitalController;

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

// Authentication Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



// Public API Routes (for patient side)
Route::get('/hospitals/rooms', [AdminController::class, 'getPublicRoomData']);
Route::get('/hospital/{hospitalId}/room-prices', [HospitalController::class, 'getRoomPrices']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/hospitals', [AdminController::class, 'getHospitals']);
        Route::post('/admin/hospitals/{hospital}/rooms/update', [AdminController::class, 'updateRoomQuantities']);
        Route::post('/admin/hospitals/{hospital}/rooms/update-all', [AdminController::class, 'updateAllRoomQuantities']);
    });
});
