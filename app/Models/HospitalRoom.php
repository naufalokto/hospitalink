<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HospitalRoom extends Model
{
    protected $fillable = [
        'hospital_id',
        'vvip_rooms',
        'class1_rooms',
        'class2_rooms',
        'class3_rooms',
    ];

    protected $casts = [
        'vvip_rooms' => 'integer',
        'class1_rooms' => 'integer',
        'class2_rooms' => 'integer',
        'class3_rooms' => 'integer',
    ];

    /**
     * Get the hospital that owns the room data
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
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
}
