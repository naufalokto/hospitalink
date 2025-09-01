<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



// Landing Page Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Selection Page Route
Route::get('/auth', function () {
    return view('auth');
})->name('auth');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('login')->with('tab', 'signup');
})->name('register');

// redirect dashboard joy

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// OAuth Routes
// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Facebook OAuth
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

// Twitter OAuth
Route::get('/auth/twitter', [AuthController::class, 'redirectToTwitter']);
Route::get('/auth/twitter/callback', [AuthController::class, 'handleTwitterCallback']);
