<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HospitalRoom extends Model
{
    protected $fillable = [
        'hospital_id',
        'facility_id',
        'vvip_rooms',
        'class1_rooms',
        'class2_rooms',
        'class3_rooms',
        'vvip_price_per_day',
        'class1_price_per_day',
        'class2_price_per_day',
        'class3_price_per_day',
    ];

    protected $casts = [
        'vvip_rooms' => 'integer',
        'class1_rooms' => 'integer',
        'class2_rooms' => 'integer',
        'class3_rooms' => 'integer',
        'vvip_price_per_day' => 'decimal:2',
        'class1_price_per_day' => 'decimal:2',
        'class2_price_per_day' => 'decimal:2',
        'class3_price_per_day' => 'decimal:2',
    ];

    /**
     * Get the hospital that owns the room data
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the facility assigned to this hospital room
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get total rooms count
     */
    public function getTotalRoomsAttribute(): int
    {
        return $this->vvip_rooms + $this->class1_rooms + $this->class2_rooms + $this->class3_rooms;
    }

    /**
     * Get room data as array
     */
    public function getRoomDataAttribute(): array
    {
        return [
            'vvip' => $this->vvip_rooms,
            'class1' => $this->class1_rooms,
            'class2' => $this->class2_rooms,
            'class3' => $this->class3_rooms,
        ];
    }

    /**
     * Get actual available rooms considering active bookings
     */
    public function getActualAvailableRoomsAttribute(): array
    {
        $activeBookings = \App\Models\Booking::where('hospital_id', $this->hospital_id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get()
            ->groupBy('room_type');

        return [
            'vvip' => max(0, $this->vvip_rooms - ($activeBookings->get('vvip', collect())->count())),
            'class1' => max(0, $this->class1_rooms - ($activeBookings->get('class1', collect())->count())),
            'class2' => max(0, $this->class2_rooms - ($activeBookings->get('class2', collect())->count())),
            'class3' => max(0, $this->class3_rooms - ($activeBookings->get('class3', collect())->count())),
        ];
    }

    /**
     * Get price for specific room type
     */
    public function getPriceForRoomType(string $roomType): float
    {
        return match($roomType) {
            'vvip' => $this->vvip_price_per_day,
            'class1' => $this->class1_price_per_day,
            'class2' => $this->class2_price_per_day,
            'class3' => $this->class3_price_per_day,
            default => 0
        };
    }

    /**
     * Get all room prices as array
     */
    public function getRoomPricesAttribute(): array
    {
        return [
            'vvip' => $this->vvip_price_per_day,
            'class1' => $this->class1_price_per_day,
            'class2' => $this->class2_price_per_day,
            'class3' => $this->class3_price_per_day,
        ];
    }

    /**
     * Get formatted price for room type
     */
    public function getFormattedPriceForRoomType(string $roomType): string
    {
        $price = $this->getPriceForRoomType($roomType);
        return 'Rp ' . number_format($price, 0, ',', '.') . ',-';
    }
}
