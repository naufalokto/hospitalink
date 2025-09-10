<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRoom extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'hospital_id',
        'room_type_id',
        'patient_name',
        'patient_phone',
        'patient_email',
        'patient_address',
        'check_in_date',
        'check_out_date',
        'duration_days',
        'price_per_day',
        'subtotal',
        'total_amount',
        'payment_id',
        'payment_status',
        'payment_method',
        'bank_code',
        'va_number',
        'transaction_id',
        'additional_data',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'additional_data' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}


