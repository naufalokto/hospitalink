<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'hospital_id',
        'room_type_id',
        'room_type', // Keep for backward compatibility during migration
        'patient_name',
        'patient_phone',
        'patient_email',
        'patient_address',
        'check_in_date',
        'check_out_date',
        'duration_days',
        'price_per_day',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'price_per_day' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK' . date('Ymd') . Str::random(6);
            }
        });
    }

    /**
     * Get the user that owns the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hospital for this booking
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the room type for this booking
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the room name from room type
     */
    public function getRoomNameAttribute(): string
    {
        return $this->roomType ? $this->roomType->name : $this->room_type;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_day, 0, ',', '.') . ',-';
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.') . ',-';
    }

    /**
     * Get the payments for the booking
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the latest payment for the booking
     */
    public function latestPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'id', 'booking_id')->latest();
    }
}
