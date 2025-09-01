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

// Debug Facebook OAuth
Route::get('/debug/facebook', function () {
    $config = [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ];
    
    $params = [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
        'response_type' => 'code',
        'state' => csrf_token(),
    ];
    
    return response()->json([
        'config' => $config,
        'facebook_url_old' => 'https://www.facebook.com/v23.0/dialog/oauth?' . http_build_query([
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'public_profile'
        ]),
        'facebook_url_new' => 'https://www.facebook.com/v23.0/dialog/oauth?' . http_build_query($params),
        'test_url' => route('auth.facebook')
    ]);
});

// Debug Facebook Callback
Route::get('/debug/facebook-callback', function () {
    return response()->json([
        'request_params' => request()->all(),
        'has_code' => request()->has('code'),
        'has_error' => request()->has('error'),
        'error' => request('error'),
        'error_description' => request('error_description'),
        'code' => request('code'),
        'state' => request('state'),
    ]);
});

// Debug User Model and Database
Route::get('/debug/user-test', function () {
    try {
        // Test database connection
        $userCount = \App\Models\User::count();
        
        // Test creating a test user
        $testUser = \App\Models\User::create([
            'name' => 'Test Facebook User',
            'email' => 'test@facebook.local',
            'facebook_id' => 'test_facebook_id_123',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        
        return response()->json([
            'database_connection' => 'OK',
            'user_count' => $userCount,
            'test_user_created' => true,
            'test_user_id' => $testUser->id,
            'test_user_facebook_id' => $testUser->facebook_id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test Facebook Callback Simulation
Route::get('/debug/test-facebook-callback', function () {
    try {
        // Simulate Facebook callback with test data
        $testCode = 'test_facebook_code_123';
        
        // Mock the request parameters
        request()->merge([
            'code' => $testCode,
            'state' => 'test_state'
        ]);
        
        // Call the Facebook callback handler
        $controller = new \App\Http\Controllers\AuthController();
        $response = $controller->handleFacebookCallback();
        
        return response()->json([
            'test_code' => $testCode,
            'response_type' => get_class($response),
            'response_status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 'N/A',
            'response_headers' => method_exists($response, 'headers') ? $response->headers->all() : 'N/A',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test Facebook OAuth with real callback
Route::get('/debug/facebook-real-test', function () {
    try {
        // Test with a real Facebook authorization code (you need to get this from Facebook)
        $realCode = request('code');
        
        if (!$realCode) {
            return response()->json([
                'error' => 'No code provided',
                'instructions' => 'Add ?code=YOUR_FACEBOOK_CODE to the URL'
            ]);
        }
        
        // Mock the request parameters
        request()->merge([
            'code' => $realCode,
            'state' => 'test_state'
        ]);
        
        // Call the Facebook callback handler
        $controller = new \App\Http\Controllers\AuthController();
        $response = $controller->handleFacebookCallback();
        
        return response()->json([
            'real_code' => $realCode,
            'response_type' => get_class($response),
            'response_status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 'N/A',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Twitter OAuth
Route::get('/auth/twitter', [AuthController::class, 'redirectToTwitter']);
Route::get('/auth/twitter/callback', [AuthController::class, 'handleTwitterCallback']);
