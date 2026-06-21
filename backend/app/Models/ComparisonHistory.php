<?php

namespace App\Models;

use Database\Factories\ComparisonHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComparisonHistory extends Model
{
    /** @use HasFactory<ComparisonHistoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'compared_motorcycle_id',
        'comparison_data',
        'summary',
    ];

    protected $casts = [
        'comparison_data' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function comparedMotorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'compared_motorcycle_id');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
