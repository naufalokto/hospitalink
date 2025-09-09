<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HospitalRoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'room_type_id',
        'rooms_count',
        'price_per_day',
    ];

    protected $casts = [
        'rooms_count' => 'integer',
        'price_per_day' => 'decimal:2',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'hospital_room_type_facility');
    }
}


