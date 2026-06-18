<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorcycle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'color',
        'license_plate',
        'engine_cc',
        'engine_type',
        'transmission',
        'fuel_type',
        'purchase_date',
        'odometer_km',
        'notes',
        'status',
        'is_favorite',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'is_favorite' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function troubleshootingHistories(): HasMany
    {
        return $this->hasMany(TroubleshootingHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }
}
