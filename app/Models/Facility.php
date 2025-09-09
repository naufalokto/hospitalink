<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility',
    ];

    /**
     * Get the hospital rooms that have this facility.
     */
    public function hospitalRoomTypes(): BelongsToMany
    {
        return $this->belongsToMany(HospitalRoomType::class, 'hospital_room_type_facility');
    }
}


