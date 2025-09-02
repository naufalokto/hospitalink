<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    }
}
