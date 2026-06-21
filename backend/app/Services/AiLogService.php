<?php

namespace App\Services;

use App\Models\AiLog;

class AiLogService
{
    /**
     * Log an AI request/response interaction.
     */
    public function log(array $data): AiLog
    {
        return AiLog::create([
            'user_id' => $data['user_id'] ?? null,
            'provider' => $data['provider'] ?? 'unknown',
            'model' => $data['model'] ?? 'unknown',
            'prompt_key' => $data['prompt_key'] ?? null,
            'request_data' => $data['request_data'] ?? null,
            'response_data' => $data['response_data'] ?? null,
            'tokens_prompt' => $data['tokens_prompt'] ?? null,
            'tokens_completion' => $data['tokens_completion'] ?? null,
            'tokens_total' => $data['tokens_total'] ?? null,
            'duration_ms' => $data['duration_ms'] ?? null,
            'status' => $data['status'] ?? 'success',
            'error_message' => $data['error_message'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
        ]);
    }

    /**
     * Log a failed AI request.
     */
    public function logError(string $provider, string $error, ?int $userId = null, ?string $promptKey = null): AiLog
    {
        return $this->log([
            'user_id' => $userId,
            'provider' => $provider,
            'status' => 'error',
            'error_message' => $error,
            'prompt_key' => $promptKey,
        ]);
    }
}
