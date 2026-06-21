<?php

namespace App\Jobs;

use App\Models\TroubleshootingHistory;
use App\Services\Ai\AiManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTroubleshootingJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public int $troubleshootingId,
        public string $prompt,
    ) {}

    public function handle(AiManager $ai): void
    {
        $history = TroubleshootingHistory::find($this->troubleshootingId);

        if (! $history) {
            return;
        }

        $response = $ai->chat($this->prompt, [
            'motorcycle' => $history->motorcycle?->toArray() ?? [],
        ]);

        $history->update([
            'ai_analysis' => $response,
            'status' => 'resolved',
        ]);
    }
}
