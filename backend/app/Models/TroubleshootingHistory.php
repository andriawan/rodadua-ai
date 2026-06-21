<?php

namespace App\Models;

use Database\Factories\TroubleshootingHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TroubleshootingHistory extends Model
{
    /** @use HasFactory<TroubleshootingHistoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'motorcycle_id',
        'user_id',
        'problem_description',
        'symptom',
        'ai_analysis',
        'suggested_solutions',
        'severity',
        'status',
        'resolution_notes',
        'resolved_date',
        'workshop_feedback',
        'ai_provider',
        'prompt_used',
        'user_rating',
        'user_feedback',
    ];

    protected $casts = [
        'suggested_solutions' => 'json',
        'resolved_date' => 'date',
    ];

    // Relationships
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }
}
