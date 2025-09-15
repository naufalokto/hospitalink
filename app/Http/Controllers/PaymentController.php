<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TransactionDetail;
use App\Models\BookingRoom;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function showDetailBooking()
    {
        // Get current user data
        $user = auth()->user();
        
        // Get hospital and room data from URL parameters
        $hospitalId = request('hospital_id');
        $roomId = request('room_id');
        
        $hospital = null;
        $roomType = null;
        $hospitalRoomType = null;
        
        if ($hospitalId && $roomId) {
            // Get hospital data
            $hospital = \App\Models\Hospital::with('roomTypes.roomType')->find($hospitalId);
            
            // Get room type data
            $roomType = \App\Models\RoomType::find($roomId);
            
            // Get hospital room type data (price)
            if ($hospital && $roomType) {
                $hospitalRoomType = \App\Models\HospitalRoomType::where('hospital_id', $hospital->id)
                    ->where('room_type_id', $roomType->id)
                    ->first();
            }
        }
        
        return view('payment.detail-booking', [
            'user' => $user,
            'hospital' => $hospital,
            'roomType' => $roomType,
            'hospitalRoomType' => $hospitalRoomType
        ]);
    }

    public function showPayment()
    {
        // Get current user data
        $user = auth()->user();
        
        return view('payment.payment', [
            'user' => $user
        ]);
    }

    /**
     * Create payment with Midtrans
     */
    public function createPayment(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('Payment creation request received', [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            try {
                $request->validate([
                    'booking_id' => 'required|exists:bookings,id',
                    'amount' => 'nullable|numeric|min:1'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Payment validation failed', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            try {
                $booking = Booking::findOrFail($request->booking_id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan.'
                ], 404);
            }
            
            // Get hospital data
            $hospital = \App\Models\Hospital::with('roomTypes.roomType')->find($booking->hospital_id);
            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rumah sakit tidak ditemukan.'
                ], 404);
            }
            
            // Compute available rooms considering active bookings (pending/confirmed)
            $available = $hospital->actual_room_data[$booking->room_type] ?? 0;
            if ($available <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar ' . $booking->room_type . ' di rumah sakit ini sudah penuh. Silakan pilih tipe kamar lain atau rumah sakit lain.'
                ], 422);
            }
            
            // Check if booking already has a pending payment
            $existingPayment = Payment::where('booking_id', $booking->id)
                ->where('status', 'pending')
                ->first();

            if ($existingPayment) {
                // Generate new snap token for existing payment
                $params = [
                    'transaction_details' => [
                        'order_id' => $existingPayment->order_id,
                        'gross_amount' => $existingPayment->amount,
                    ],
                    'customer_details' => [
                        'first_name' => $booking->patient_name,
                        'email' => $booking->patient_email ?? 'patient@example.com',
                        'phone' => $booking->patient_phone,
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
                
                try {
                    $snapToken = Snap::getSnapToken($params);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat Snap token untuk payment yang sudah ada: ' . $e->getMessage()
                    ], 500);
                }
                
                $response = [
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_id' => $existingPayment->order_id,
                    'amount' => $existingPayment->amount,
                    'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                    'payment_id' => $existingPayment->id
                ];
                
                return response()->json($response);
            }

            // Use amount from request (calculated from duration) or fallback to booking total
            $amount = $request->amount ?? $booking->total_price;
            
            // Prepare Midtrans parameters
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

            // Create Snap token
            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat Snap token: ' . $e->getMessage()
                ], 500);
            }
            
            // Create payment record first
            try {
                $payment = Payment::create([
                    'order_id' => $params['transaction_details']['order_id'],
                    'booking_id' => $booking->id,
                    'payment_type' => 'snap',
                    'bank_code' => null,
                    'va_number' => null, // Will be filled by Midtrans
                    'amount' => $request->amount ?? $booking->total_price,
                    'status' => 'pending',
                    'transaction_id' => null,
                    'midtrans_response' => $params,
                    'expired_at' => now()->addHours(24),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat record pembayaran: ' . $e->getMessage()
                ], 500);
            }
            
            $response = [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'payment_id' => $payment->id
            ];
            
            // Log successful payment creation
            Log::info('Payment created successfully', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'amount' => $payment->amount,
                'redirect_url' => $response['redirect_url'],
                'booking_id' => $booking->id
            ]);
            
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();
            
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $transactionId = $notification->transaction_id ?? null;

            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Verify amount
            if ($payment->amount != $grossAmount) {
                return response()->json(['message' => 'Amount mismatch'], 400);
            }

            // Extract VA number if provided by Midtrans
            $vaNumber = $payment->va_number;
            if (isset($notification->va_numbers) && is_array($notification->va_numbers) && count($notification->va_numbers) > 0) {
                // va_numbers is an array of objects { bank, va_number }
                $firstVa = $notification->va_numbers[0];
                $vaNumber = $firstVa->va_number ?? $vaNumber;
            } elseif (isset($notification->permata_va_number)) {
                $vaNumber = $notification->permata_va_number;
            }

            // Update payment status and details
            $payment->update([
                'status' => $transactionStatus,
                'transaction_id' => $transactionId,
                'va_number' => $vaNumber,
                'midtrans_response' => $notification->getResponse()
            ]);

            // Update booking status based on payment status
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $payment->booking->update(['status' => 'confirmed']);
                
                // Create transaction detail
                $this->createTransactionDetail($payment);
                
                // Decrease room availability
                $this->decreaseRoomAvailability($payment->booking);

                // Mark booking room snapshot as paid
                try {
                    BookingRoom::where('booking_id', $payment->booking->id)
                        ->latest('id')
                        ->first()?->update([
                            'payment_id' => $payment->id,
                            'payment_status' => $transactionStatus,
                            'payment_method' => $payment->payment_type,
                            'bank_code' => $payment->bank_code,
                            'va_number' => $payment->va_number,
                            'transaction_id' => $payment->transaction_id,
                        ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to update BookingRoom after payment', [
                        'error' => $e->getMessage(),
                        'booking_id' => $payment->booking->id,
                    ]);
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $payment->booking->update(['status' => 'cancelled']);
            }

            Log::info('Midtrans notification processed', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'va_number' => $payment->va_number,
                'transaction_id' => $payment->transaction_id
            ]);

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            Log::error('Notification handling failed: ' . $e->getMessage());
            return response()->json(['message' => 'Notification processing failed'], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // For testing purposes, we'll use the payment status from database
            // In production, you would check Midtrans API here
            Log::info('Payment status check', [
                'order_id' => $orderId,
                'current_status' => $payment->status,
                'va_number' => $payment->va_number,
                'amount' => $payment->amount
            ]);

            return response()->json([
                'order_id' => $payment->order_id,
                'status' => $payment->status,
                'va_number' => $payment->va_number,
                'amount' => $payment->amount,
                'expired_at' => $payment->expired_at,
                'booking_status' => $payment->booking->status
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage());
            return response()->json(['message' => 'Status check failed'], 500);
        }
    }

    /**
     * Simulate successful payment (for testing)
     */
    public function simulatePaymentSuccess($orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment status to settlement
            $payment->update([
                'status' => 'settlement',
                'midtrans_response' => array_merge($payment->midtrans_response ?? [], [
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept'
                ])
            ]);

            // Update booking status
            $payment->booking->update(['status' => 'confirmed']);
            
            // Create transaction detail
            $this->createTransactionDetail($payment);
            
            // Decrease room availability
            $this->decreaseRoomAvailability($payment->booking);

            return response()->json([
                'success' => true,
                'message' => 'Payment simulation successful',
                'payment_status' => 'settlement',
                'booking_status' => 'confirmed'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment simulation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Simulation failed'], 500);
        }
    }

    /**
     * Simulate failed payment (for testing)
     */
    public function simulatePaymentFailure($orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment status to failure
            $payment->update([
                'status' => 'failure',
                'midtrans_response' => array_merge($payment->midtrans_response ?? [], [
                    'transaction_status' => 'failure',
                    'fraud_status' => 'deny'
                ])
            ]);

            // Update booking status
            $payment->booking->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Payment simulation failed',
                'payment_status' => 'failure',
                'booking_status' => 'cancelled'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment simulation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Simulation failed'], 500);
        }
    }

    /**
     * Handle successful payment callback from Midtrans
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            if ($orderId) {
                $payment = Payment::where('order_id', $orderId)->first();
                
                if ($payment) {
                    // Verify with Midtrans for the latest status
                    try {
                        $status = Transaction::status($orderId);
                        $transactionStatus = $status->transaction_status ?? 'settlement';

                        // Extract VA number if any
                        $vaNumber = $payment->va_number;
                        if (isset($status->va_numbers) && is_array($status->va_numbers) && count($status->va_numbers) > 0) {
                            $vaNumber = $status->va_numbers[0]->va_number ?? $vaNumber;
                        } elseif (isset($status->permata_va_number)) {
                            $vaNumber = $status->permata_va_number;
                        }

                        $payment->update([
                            'status' => $transactionStatus,
                            'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                            'va_number' => $vaNumber,
                            'midtrans_response' => $status
                        ]);

                        if (in_array($transactionStatus, ['settlement', 'capture'])) {
                            $payment->booking->update(['status' => 'confirmed']);
                            
                            // Create transaction detail
                            $this->createTransactionDetail($payment);
                            
                            $this->decreaseRoomAvailability($payment->booking);
                        }
                    } catch (\Exception $inner) {
                        Log::warning('Failed to verify payment status on success callback, falling back to settlement: ' . $inner->getMessage());
                        $payment->update([
                            'status' => 'settlement',
                            'midtrans_response' => array_merge($payment->midtrans_response ?? [], [
                                'transaction_status' => 'settlement'
                            ])
                        ]);
                        $payment->booking->update(['status' => 'confirmed']);
                        
                        // Create transaction detail
                        $this->createTransactionDetail($payment);
                        
                        $this->decreaseRoomAvailability($payment->booking);
                    }
                }
            }
            
            // Redirect to checking-detail of the booked hospital and room type, with success popup
            if (isset($payment) && $payment->booking) {
                $hospital = $payment->booking->hospital;
                $roomType = $payment->booking->room_type; // vvip|class1|class2|class3
                $roomIdMap = ['vvip' => 1, 'class1' => 2, 'class2' => 3, 'class3' => 4];
                $roomId = $roomIdMap[$roomType] ?? 1;
                $slugOrId = $hospital?->slug ?? $payment->booking->hospital_id;
                $url = route('checking-detail', ['hospital_id' => $slugOrId, 'room_id' => $roomId]);
                // add ?paid=1 to trigger popup
                return redirect()->to($url . '?paid=1');
            }
            
            // Fallback: Jika tidak ada payment data, redirect ke room dengan success message
            return redirect()->to(route('room') . '?booking_success=1');
            
        } catch (\Exception $e) {
            Log::error('Payment success callback failed: ' . $e->getMessage());
            return redirect()->to(route('room') . '?booking_success=1');
        }
    }

    /**
     * Handle failed payment callback from Midtrans
     */
    public function paymentFailed(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            if ($orderId) {
                $payment = Payment::where('order_id', $orderId)->first();
                
                if ($payment) {
                    // Try to verify with Midtrans first
                    try {
                        $status = Transaction::status($orderId);
                        $transactionStatus = $status->transaction_status ?? 'failure';
                        $payment->update([
                            'status' => $transactionStatus,
                            'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                            'midtrans_response' => $status
                        ]);
                    } catch (\Exception $inner) {
                        Log::warning('Failed to verify payment status on failed callback, falling back to failure: ' . $inner->getMessage());
                        $payment->update([
                            'status' => 'failure',
                            'midtrans_response' => array_merge($payment->midtrans_response ?? [], [
                                'transaction_status' => 'failure'
                            ])
                        ]);
                    }

                    // Update booking status
                    $payment->booking->update(['status' => 'cancelled']);
                }
            }
            
            // Redirect back to checking page of the hospital without success popup
            if (isset($payment) && $payment->booking) {
                $hospital = $payment->booking->hospital;
                $slugOrId = $hospital?->slug ?? $payment->booking->hospital_id;
                return redirect()->to(route('checking', ['hospital_id' => $slugOrId]));
            }
            return redirect()->to(route('room'));
            
        } catch (\Exception $e) {
            Log::error('Payment failed callback failed: ' . $e->getMessage());
            return redirect()->to(route('payment.pay'));
        }
    }

    /**
     * Generate fallback VA number for testing
     */
    private function generateFallbackVANumber($bankCode)
    {
        $bankPrefixes = [
            'BCA' => '1234567890',
            'BRI' => '2345678901', 
            'BNI' => '3456789012',
            'Mandiri' => '4567890123',
            'CIMB' => '5678901234',
            'BSI' => '6789012345'
        ];
        
        $prefix = $bankPrefixes[$bankCode] ?? '9999999999';
        $suffix = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $suffix;
    }

    /**
     * Create transaction detail after successful payment
     */
    private function createTransactionDetail(Payment $payment)
    {
        try {
            $booking = $payment->booking;
            $hospital = $booking->hospital;
            $roomType = $booking->roomType;
            
            // Check if transaction detail already exists
            $existingTransaction = TransactionDetail::where('payment_id', $payment->id)->first();
            if ($existingTransaction) {
                return $existingTransaction;
            }

            // Get user data for transaction detail
            $user = $booking->user;
            
            // Create transaction detail
            $transactionDetail = TransactionDetail::create([
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'hospital_id' => $booking->hospital_id,
                'room_type_id' => $booking->room_type_id,
                'patient_name' => $user->name, // Use real user name
                'patient_phone' => $booking->patient_phone,
                'patient_email' => $user->email, // Use real user email
                'patient_address' => $booking->patient_address,
                'hospital_name' => $hospital->name,
                'room_type_name' => $roomType ? $roomType->name : $booking->room_name,
                'room_type_code' => $booking->room_type,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'duration_days' => $booking->duration_days,
                'price_per_day' => $booking->price_per_day,
                'subtotal' => $booking->total_price,
                'total_amount' => $booking->total_price,
                'payment_method' => $payment->payment_type,
                'bank_code' => $payment->bank_code,
                'va_number' => $payment->va_number,
                'transaction_id' => $payment->transaction_id,
                'status' => 'completed',
                'payment_completed_at' => now(),
                'additional_data' => [
                    'booking_number' => $booking->booking_number,
                    'order_id' => $payment->order_id,
                    'midtrans_response' => $payment->midtrans_response
                ],
                'notes' => $booking->notes
            ]);

            Log::info('Transaction detail created', [
                'transaction_number' => $transactionDetail->transaction_number,
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'total_amount' => $transactionDetail->total_amount
            ]);

            return $transactionDetail;

        } catch (\Exception $e) {
            Log::error('Failed to create transaction detail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Decrease room availability after successful payment
     */
    private function decreaseRoomAvailability(Booking $booking)
    {
        \DB::transaction(function () use ($booking) {
            // Get room type model
            $roomType = \App\Models\RoomType::where('code', $booking->room_type)->first();
            
            if (!$roomType) {
                return; // No room type found
            }

            // Find or create hospital room type record
            $hospitalRoomType = \App\Models\HospitalRoomType::where('hospital_id', $booking->hospital_id)
                ->where('room_type_id', $roomType->id)
                ->lockForUpdate()
                ->first();

            if (!$hospitalRoomType) {
                return; // No room record for this hospital and room type
            }

            // Decrease available rooms count
            if ($hospitalRoomType->rooms_count > 0) {
                $hospitalRoomType->decrement('rooms_count');
            }
        });
    }
}
