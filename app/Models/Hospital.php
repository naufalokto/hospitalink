<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'description',
        'public_service',
        'image_url',
        'website_url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hospital) {
            if (!$hospital->slug) {
                $hospital->slug = Str::slug($hospital->name);
            }
        });

        // Create room data when hospital is created
        static::created(function ($hospital) {
            $hospital->room()->create([
                'vvip_rooms' => 10,
                'class1_rooms' => 10,
                'class2_rooms' => 10,
                'class3_rooms' => 10,
            ]);
        });
    }

    /**
     * Get the room data for the hospital
     */
    public function room(): HasOne
    {
        return $this->hasOne(HospitalRoom::class);
    }

    /**
     * Get room data with fallback
     */
    public function getRoomDataAttribute(): array
    {
        $room = $this->room;
        if (!$room) {
            return [
                'vvip' => 0,
                'class1' => 0,
                'class2' => 0,
                'class3' => 0,
            ];
        }
        
        return $room->room_data;
    }

    /**
     * Get actual available rooms considering bookings
     */
    public function getActualRoomDataAttribute(): array
    {
        $room = $this->room;
        if (!$room) {
            return [
                'vvip' => 0,
                'class1' => 0,
                'class2' => 0,
                'class3' => 0,
            ];
        }
        
        return $room->actual_available_rooms;
    }
}
