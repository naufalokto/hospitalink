<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'admission_requirements',
        'room_prices',
        'phone_number',
        'facilities',
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

        // Create room types when hospital is created
        static::created(function ($hospital) {
            $roomTypes = RoomType::all();
            foreach ($roomTypes as $roomType) {
                $hospital->roomTypes()->create([
                    'room_type_id' => $roomType->id,
                    'rooms_count' => 10, // Default room count
                    'price_per_day' => 0, // Will be set later
                ]);
            }
        });
    }

    /**
     * Get the room data for the hospital (from room types)
     */
    public function getRoomDataAttribute(): array
    {
        $roomTypes = $this->roomTypes;
        $roomData = [
            'vvip' => 0,
            'class1' => 0,
            'class2' => 0,
            'class3' => 0,
        ];
        
        foreach ($roomTypes as $roomType) {
            $code = $roomType->roomType->code;
            if (isset($roomData[$code])) {
                $roomData[$code] = $roomType->rooms_count;
            }
        }
        
        return $roomData;
    }

    /**
     * Normalized: room types for this hospital
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(HospitalRoomType::class);
    }

    /**
     * Get actual available rooms considering bookings
     */
    public function getActualRoomDataAttribute(): array
    {
        $roomData = $this->room_data;
        $bookings = $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
        
        foreach ($bookings as $booking) {
            $roomType = $booking->roomType;
            if ($roomType && isset($roomData[$roomType->code])) {
                $roomData[$roomType->code] = max(0, $roomData[$roomType->code] - 1);
            }
        }
        
        return $roomData;
    }

    /**
     * Get bookings for this hospital
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
