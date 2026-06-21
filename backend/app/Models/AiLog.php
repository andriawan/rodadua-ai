<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'model',
        'prompt_key',
        'request_data',
        'response_data',
        'tokens_prompt',
        'tokens_completion',
        'tokens_total',
        'duration_ms',
        'status',
        'error_message',
        'ip_address',
    ];

    protected $casts = [
        'request_data' => 'json',
        'response_data' => 'json',
        'tokens_prompt' => 'integer',
        'tokens_completion' => 'integer',
        'tokens_total' => 'integer',
        'duration_ms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        return $query->latest()->limit(50);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
