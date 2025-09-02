<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'image',
        'source',
        'content'
    ];

    // Get news by slug
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}
