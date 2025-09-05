<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HospitalController;



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

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); 
})->name('logout');

Route::get('/register', function () {
    return redirect()->route('login')->with('tab', 'signup');
})->name('register');

// redirect dashboard joy

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/admin-dashboard', function () {
    return view('admin-dashboard');
})->name('admin-dashboard')->middleware('admin');

Route::get('/hospital', [HospitalController::class, 'index'])->name('hospital');
Route::get('/hospital/{slug}', [HospitalController::class, 'show'])->name('hospital.detail');

Route::get('/room', function () {
    return view('room');
})->name('room');

Route::get('/checking/{hospital_id}', [App\Http\Controllers\RoomController::class, 'checking'])->name('checking');
Route::get('/checking/{hospital_id}/room/{room_id}', [App\Http\Controllers\RoomController::class, 'checkingDetail'])->name('checking-detail');

Route::get('/help', function () {
    return view('help');
})->name('help');

// News routes
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.detail');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

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

// Debug News Data
Route::get('/debug/news-test', function () {
    try {
        $news = \App\Models\News::all();
        $newsData = $news->map(function($item) {
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'image' => $item->image,
                'image_url' => asset($item->image),
                'source' => $item->source,
                'content_length' => strlen($item->content)
            ];
        });
        
        return response()->json([
            'news_count' => $news->count(),
            'news_data' => $newsData,
            'asset_helper_test' => [
                'news1' => asset('images/news/news-card1.png'),
                'news2' => asset('images/news/news-card2.jpg'),
                'news3' => asset('images/news/news-card3.jpg')
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
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

// Test Asset Helper for Production
Route::get('/debug/asset-test', function () {
    return response()->json([
        'current_app_url' => config('app.url'),
        'asset_helper_test' => [
            'news1' => asset('images/news/news-card1.png'),
            'news2' => asset('images/news/news-card2.jpg'),
            'news3' => asset('images/news/news-card3.jpg'),
            'logo' => asset('images/Logo-Hospitalink.png')
        ],
        'url_helper_test' => [
            'news1' => url('images/news/news-card1.png'),
            'news2' => url('images/news/news-card2.jpg')
        ]
    ]);
});

// Debug Hospital Data
Route::get('/debug/hospital-test', function () {
    try {
        $hospitals = \App\Models\Hospital::all();
        $hospitalData = $hospitals->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'image_url' => $item->image_url,
                'image_asset' => asset($item->image_url),
                'website_url' => $item->website_url
            ];
        });
        
        return response()->json([
            'hospital_count' => $hospitals->count(),
            'hospitals' => $hospitalData,
            'file_check' => [
                'rsud_sidoarjo' => file_exists(public_path('images/hospitals/rsud_sidoarjo.jpg')),
                'rsud_suwandi' => file_exists(public_path('images/hospitals/rsud_suwandi.jpg')),
                'rsud_wahidin' => file_exists(public_path('images/hospitals/rsud_wahidin.jpg'))
            ]
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