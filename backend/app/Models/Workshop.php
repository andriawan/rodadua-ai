<?php

namespace App\Models;

use Database\Factories\WorkshopFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    /** @use HasFactory<WorkshopFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'website',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'rating',
        'total_reviews',
        'specialist_motorcycle_count',
        'operating_hours',
        'is_open_weekends',
        'services_offered',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_open_weekends' => 'boolean',
        'services_offered' => 'json',
    ];

    // Scopes
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4.0)->orderByDesc('rating');
    }

    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        // Simple distance calculation (more precise would use haversine formula)
        $latOffset = $radiusKm / 111; // Rough conversion
        
        return $query->whereBetween('latitude', [$latitude - $latOffset, $latitude + $latOffset])
                    ->whereBetween('longitude', [$longitude - $latOffset, $longitude + $latOffset]);
    }
}
