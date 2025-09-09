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

// Create Test Booking for Payment Testing
Route::get('/debug/create-test-booking', function () {
    try {
        // Create a test user if not exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'test@payment.com'],
            [
                'name' => 'Test User Payment',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'patient'
            ]
        );

        // Get first hospital
        $hospital = \App\Models\Hospital::first();
        if (!$hospital) {
            return response()->json(['error' => 'No hospital found'], 404);
        }

        // Check if user already has a booking in this hospital
        $existingBooking = \App\Models\Booking::where('user_id', $user->id)
            ->where('hospital_id', $hospital->id)
            ->first();

        if ($existingBooking) {
            // Update existing booking
            $existingBooking->update([
                'room_type' => 'vvip',
                'check_in_date' => now()->addDays(1),
                'check_out_date' => now()->addDays(6),
                'duration_days' => 5,
                'price_per_day' => 300000,
                'total_price' => 1500000,
                'status' => 'pending'
            ]);
            $booking = $existingBooking;
        } else {
            // Create test booking
            $booking = \App\Models\Booking::create([
                'user_id' => $user->id,
                'hospital_id' => $hospital->id,
                'room_type' => 'vvip',
                'patient_name' => 'Test Patient Payment',
                'patient_phone' => '081234567890',
                'patient_email' => 'test@payment.com',
                'check_in_date' => now()->addDays(1),
                'check_out_date' => now()->addDays(6),
                'duration_days' => 5,
                'price_per_day' => 300000,
                'total_price' => 1500000,
                'status' => 'pending'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Test booking created successfully',
            'booking' => [
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'patient_name' => $booking->patient_name,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'hospital_name' => $hospital->name
            ],
            'payment_url' => route('payment.detail-booking') . '?booking_id=' . $booking->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create test booking: ' . $e->getMessage()
        ], 500);
    }
});

// Test All Payment Methods
Route::get('/debug/test-payment-methods', function () {
    $banks = ['BCA', 'BRI', 'BNI', 'Mandiri', 'CIMB', 'BSI'];
    $results = [];
    
    foreach ($banks as $bank) {
        try {
            // Test bank code validation
            $validator = \Illuminate\Support\Facades\Validator::make(
                ['bank_code' => $bank],
                ['bank_code' => 'required|in:BCA,BRI,BNI,Mandiri,CIMB,BSI']
            );
            
            $results[$bank] = [
                'valid' => !$validator->fails(),
                'errors' => $validator->errors()->toArray()
            ];
        } catch (\Exception $e) {
            $results[$bank] = [
                'valid' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    return response()->json([
        'status' => 'success',
        'message' => 'Payment methods validation test',
        'results' => $results,
        'supported_banks' => $banks,
        'total_banks' => count($banks)
    ]);
});

// Test Payment Creation
Route::post('/debug/test-payment-creation', function (Request $request) {
    try {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'bank_code' => 'required|in:BCA,BRI,BNI,Mandiri,CIMB,BSI'
        ]);

        $booking = \App\Models\Booking::findOrFail($request->booking_id);
        
        // Simulate payment creation without Midtrans
        $orderId = $booking->booking_number . '-' . time();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment creation test successful',
            'data' => [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'bank_code' => $bank_code,
                'order_id' => $orderId,
                'amount' => $booking->total_price,
                'patient_name' => $booking->patient_name
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Payment creation test failed: ' . $e->getMessage()
        ], 500);
    }
});

// API to get room prices for a hospital
Route::get('/api/hospital/{hospital_id}/room-prices', function ($hospital_id) {
    try {
        $hospital = \App\Models\Hospital::with('roomTypes.roomType')->find($hospital_id);
        
        if (!$hospital) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hospital not found'
            ], 404);
        }
        
        $roomPrices = [];
        $availableRooms = $hospital->actual_room_data;
        
        foreach ($hospital->roomTypes as $hospitalRoomType) {
            $roomType = $hospitalRoomType->roomType;
            $roomPrices[$roomType->code] = $hospitalRoomType->price_per_day;
        }
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'hospital_id' => $hospital_id,
                'hospital_name' => $hospital->name,
                'hospital_slug' => $hospital->slug,
                'room_prices' => $roomPrices,
                'available_rooms' => $availableRooms
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to get room prices: ' . $e->getMessage()
        ], 500);
    }
});

// Debug API: create a pending booking for a specific hospital and room type
Route::get('/api/debug/create-booking/{hospital_id}/{room_type}', function ($hospital_id, $room_type) {
    try {
        if (!in_array($room_type, ['vvip', 'class1', 'class2', 'class3'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid room type'], 422);
        }

        $hospital = \App\Models\Hospital::with('roomTypes.roomType')->findOrFail($hospital_id);
        
        // Get room type and price from database
        $roomTypeModel = \App\Models\RoomType::where('code', $room_type)->first();
        if (!$roomTypeModel) {
            return response()->json(['status' => 'error', 'message' => 'Invalid room type'], 422);
        }
        
        $hospitalRoomType = $hospital->roomTypes()->where('room_type_id', $roomTypeModel->id)->first();
        if (!$hospitalRoomType) {
            return response()->json(['status' => 'error', 'message' => 'Room type not available for this hospital'], 422);
        }
        
        $pricePerDay = $hospitalRoomType->price_per_day;

        // Create a test user if not exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'test@payment.com'],
            [
                'name' => 'Test User Payment',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'patient'
            ]
        );

        $now = now();
        $durationDays = 5;

        // Check if user already has a booking in this hospital
        $existingBooking = \App\Models\Booking::where('user_id', $user->id)
            ->where('hospital_id', $hospital->id)
            ->first();

        if ($existingBooking) {
            // Update existing booking instead of creating new one
            $existingBooking->update([
                'room_type_id' => $roomTypeModel->id,
                'room_type' => $room_type,
                'check_in_date' => $now->copy()->addDay(),
                'check_out_date' => $now->copy()->addDays(1 + $durationDays),
                'duration_days' => $durationDays,
                'price_per_day' => $pricePerDay,
                'total_price' => $pricePerDay * $durationDays,
                'status' => 'pending'
            ]);
            $booking = $existingBooking;
        } else {
            // Create new booking
            $booking = \App\Models\Booking::create([
                'user_id' => $user->id,
                'hospital_id' => $hospital->id,
                'room_type_id' => $roomTypeModel->id,
                'room_type' => $room_type, // Keep for backward compatibility
                'patient_name' => 'Test Patient Payment',
                'patient_phone' => '081234567890',
                'patient_email' => 'test@payment.com',
                'check_in_date' => $now->copy()->addDay(),
                'check_out_date' => $now->copy()->addDays(1 + $durationDays),
                'duration_days' => $durationDays,
                'price_per_day' => $pricePerDay,
                'total_price' => $pricePerDay * $durationDays,
                'status' => 'pending'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'hospital_id' => $hospital->id,
                'room_type' => $room_type,
                'price_per_day' => $pricePerDay,
                'total_price' => $booking->total_price,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create booking: ' . $e->getMessage()
        ], 500);
    }
});

// Debug Payment Creation (No Auth Required) - GET for testing
Route::get('/debug/payment-create/{booking_id}/{bank_code}/{amount}/{room_type?}/{days?}/{price_per_day?}', function ($booking_id, $bank_code, $amount, $room_type = 'class2', $days = 5, $price_per_day = 300000) {
    try {
        // Validate parameters
        if (!in_array($bank_code, ['BCA', 'BRI', 'BNI', 'Mandiri', 'CIMB', 'BSI'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid bank code'], 400);
        }
        
        if (!in_array($room_type, ['vvip', 'class1', 'class2', 'class3'])) {
            $room_type = 'class2';
        }

        $booking = \App\Models\Booking::findOrFail($booking_id);
        
        // Log received data for debugging
        \Log::info('Payment creation request', [
            'booking_id' => $booking_id,
            'bank_code' => $bank_code,
            'amount' => $amount,
            'room_type' => $room_type,
            'days' => $days,
            'price_per_day' => $price_per_day
        ]);
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        // midtrans parameter lek jare faler
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_number . '-' . time(),
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $booking->patient_name,
                'email' => $booking->patient_email ?? 'patient@example.com',
                'phone' => $booking->patient_phone,
            ],
            'payment_type' => 'bank_transfer',
            'bank_transfer' => [
                'bank' => strtolower($bank_code),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ],
            // uri buat callback lah intie
            'callbacks' => [
                'finish' => route('payment.success'),
                'unfinish' => route('payment.failed'),
                'error' => route('payment.failed'),
            ]
        ];
        
        // Test Snap token creation
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // Create payment record
        $payment = \App\Models\Payment::create([
            'order_id' => $params['transaction_details']['order_id'],
            'booking_id' => $booking->id,
            'payment_type' => 'bank_transfer',
            'bank_code' => $bank_code,
            'va_number' => null,
            'amount' => $amount,
            'status' => 'pending',
            'transaction_id' => null,
            'midtrans_response' => $params,
            'expired_at' => now()->addHours(24),
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully',
            'data' => [
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'bank_code' => $payment->bank_code,
                'snap_token' => $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Payment creation failed: ' . $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ], 500);
    }
});

// Test Payment Creation with Midtrans (simplified) - No Auth Required
Route::post('/debug/test-midtrans-payment', function (Request $request) {
    try {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'bank_code' => 'required|in:BCA,BRI,BNI,Mandiri,CIMB,BSI'
        ]);

        $booking = \App\Models\Booking::findOrFail($request->booking_id);
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        // Prepare Midtrans parameters
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_number . '-' . time(),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->patient_name,
                'email' => $booking->patient_email ?? 'patient@example.com',
                'phone' => $booking->patient_phone,
            ],
            'payment_type' => 'bank_transfer',
            'bank_transfer' => [
                'bank' => strtolower($bank_code),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ]
        ];
        
        // Test Snap token creation
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Midtrans payment test successful',
            'data' => [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'bank_code' => $bank_code,
                'order_id' => $params['transaction_details']['order_id'],
                'amount' => $booking->total_price,
                'snap_token' => $snapToken,
                'patient_name' => $booking->patient_name
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Midtrans payment test failed: ' . $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ], 500);
    }
});