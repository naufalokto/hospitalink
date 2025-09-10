<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\RoomViewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;


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
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');

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

Route::get('/admin-dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin-dashboard')->middleware('admin');

// Admin update routes
Route::post('/admin/update-hospital', [App\Http\Controllers\AdminController::class, 'updateAllRoomQuantitiesWeb'])->name('admin.update-hospital')->middleware(['admin', 'web']);


Route::get('/hospital', [HospitalController::class, 'index'])->name('hospital');
Route::get('/hospital/{slug}', [HospitalController::class, 'show'])->name('hospital.detail');

Route::get('/room', [RoomViewController::class, 'index'])->name('room');

Route::get('/checking/{hospital_id}', [App\Http\Controllers\RoomController::class, 'checking'])->name('checking');
Route::get('/checking/{hospital_id}/room/{room_id}', [App\Http\Controllers\RoomController::class, 'checkingDetail'])->name('checking-detail');

// Booking routes
Route::middleware('auth')->group(function () {
    Route::get('/booking/{hospital_id}/room/{room_id}', [App\Http\Controllers\BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/booking/{hospital_id}/room/{room_id}', [App\Http\Controllers\BookingController::class, 'processBooking'])->name('booking.process');
    Route::get('/booking/{booking_id}/invoice', [App\Http\Controllers\BookingController::class, 'showInvoice'])->name('booking.invoice');
    Route::get('/booking/{booking_id}/download', [App\Http\Controllers\BookingController::class, 'downloadInvoice'])->name('booking.download');
    Route::get('/my-bookings', [App\Http\Controllers\BookingController::class, 'myBookings'])->name('my-bookings');
    Route::get('/invoice', [App\Http\Controllers\BookingController::class, 'invoice'])->name('invoice');
});

// Payment Routes (require authentication)
Route::prefix('payment')->middleware(['auth'])->group(function () {
    Route::get('/detail-booking', [App\Http\Controllers\PaymentController::class, 'showDetailBooking'])->name('payment.detail-booking');
    Route::get('/pay', [App\Http\Controllers\PaymentController::class, 'showPayment'])->name('payment.pay');
    
    // Midtrans Payment Routes
    Route::post('/create', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/status/{orderId}', [App\Http\Controllers\PaymentController::class, 'checkPaymentStatus'])->name('payment.status');
    
    // Payment Simulation Routes (for testing)
    Route::post('/simulate/success/{orderId}', [App\Http\Controllers\PaymentController::class, 'simulatePaymentSuccess'])->name('payment.simulate-success');
    Route::post('/simulate/failure/{orderId}', [App\Http\Controllers\PaymentController::class, 'simulatePaymentFailure'])->name('payment.simulate-failure');
});

// Midtrans Notification Route (no auth required - called by Midtrans)
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'handleNotification'])->name('payment.notification');

// Midtrans Callback Routes (no auth required)
Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed', [App\Http\Controllers\PaymentController::class, 'paymentFailed'])->name('payment.failed');

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

// Test Midtrans Configuration
Route::get('/debug/midtrans-test', function () {
    try {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        $isSandbox = config('midtrans.is_sandbox');
        
        return response()->json([
            'server_key' => $serverKey ? 'Set' : 'Not Set',
            'client_key' => $clientKey ? 'Set' : 'Not Set',
            'is_production' => $isProduction,
            'is_sandbox' => $isSandbox,
            'server_key_preview' => $serverKey ? substr($serverKey, 0, 20) . '...' : 'Not Set',
            'client_key_preview' => $clientKey ? substr($clientKey, 0, 20) . '...' : 'Not Set',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test Midtrans Connection
Route::get('/debug/midtrans-connection', function () {
    try {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        // Test connection with a simple transaction status check
        $testOrderId = 'test-order-' . time();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Midtrans configuration loaded successfully',
            'server_key_preview' => substr(config('midtrans.server_key'), 0, 20) . '...',
            'client_key_preview' => substr(config('midtrans.client_key'), 0, 20) . '...',
            'is_production' => config('midtrans.is_production'),
            'is_sandbox' => config('midtrans.is_sandbox'),
            'test_order_id' => $testOrderId,
            'ready_for_payment' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Midtrans configuration error: ' . $e->getMessage()
        ], 500);
    }
});

// Debug Payment Flow
Route::get('/debug/payment-flow', function () {
    try {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Get latest booking for user
        $latestBooking = \App\Models\Booking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
            
        if (!$latestBooking) {
            return response()->json(['error' => 'No booking found for user'], 404);
        }
        
        // Test payment creation
        $params = [
            'transaction_details' => [
                'order_id' => $latestBooking->booking_number . '-' . time(),
                'gross_amount' => $latestBooking->total_price,
            ],
            'customer_details' => [
                'first_name' => $latestBooking->patient_name,
                'email' => $latestBooking->patient_email ?? 'patient@example.com',
                'phone' => $latestBooking->patient_phone,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ],
            'callbacks' => [
                'finish' => route('payment.success'),
                'unfinish' => route('payment.failed'),
                'error' => route('payment.failed')
            ]
        ];
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;
        
        return response()->json([
            'status' => 'success',
            'booking' => [
                'id' => $latestBooking->id,
                'booking_number' => $latestBooking->booking_number,
                'total_price' => $latestBooking->total_price,
                'status' => $latestBooking->status,
                'patient_name' => $latestBooking->patient_name,
                'patient_email' => $latestBooking->patient_email,
                'patient_phone' => $latestBooking->patient_phone,
            ],
            'payment_params' => $params,
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
            'test_redirect' => true
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Payment flow test failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->middleware('auth');

