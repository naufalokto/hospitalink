<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_number',
        'payment_id',
        'booking_id',
        'user_id',
        'hospital_id',
        'room_type_id',
        'patient_name',
        'patient_phone',
        'patient_email',
        'patient_address',
        'hospital_name',
        'room_type_name',
        'room_type_code',
        'check_in_date',
        'check_out_date',
        'duration_days',
        'price_per_day',
        'subtotal',
        'total_amount',
        'payment_method',
        'bank_code',
        'va_number',
        'transaction_id',
        'status',
        'payment_completed_at',
        'additional_data',
        'notes'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_completed_at' => 'datetime',
        'additional_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transactionDetail) {
            if (!$transactionDetail->transaction_number) {
                $transactionDetail->transaction_number = 'TXN' . date('Ymd') . Str::random(8);
            }
        });
    }

    /**
     * Get the payment that owns the transaction detail
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the booking that owns the transaction detail
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user that owns the transaction detail
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hospital for this transaction detail
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the room type for this transaction detail
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get formatted price per day
     */
    public function getFormattedPricePerDayAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_day, 0, ',', '.') . ',-';
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.') . ',-';
    }


    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.') . ',-';
    }

    /**
     * Check if transaction is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if transaction is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status text in Indonesian
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'completed' => 'Selesai',
            'pending' => 'Menunggu',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            default => 'Tidak Diketahui'
        };
    }
}