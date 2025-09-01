<?php

use Illuminate\Support\Facades\Route;



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

Route::post('/login', function () {
    // Handle login form submission
    // Add your authentication logic here
    return redirect()->back()->with('message', 'Login functionality to be implemented');
})->name('login.submit');

Route::post('/register', function () {
    // Handle registration form submission
    // Add your user registration logic here
    return redirect()->back()->with('message', 'Registration functionality to be implemented');
})->name('register.submit');
