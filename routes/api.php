<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// OAuth Routes (need session)
Route::middleware('web')->group(function () {
    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    
    // Facebook OAuth
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook']);
    Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);
    
    // Twitter OAuth
    Route::get('/auth/twitter', [AuthController::class, 'redirectToTwitter']);
    Route::get('/auth/twitter/callback', [AuthController::class, 'handleTwitterCallback']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
});
