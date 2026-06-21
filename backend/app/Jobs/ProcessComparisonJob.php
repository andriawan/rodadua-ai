<?php

namespace App\Jobs;

use App\Models\ComparisonHistory;
use App\Services\Ai\AiManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessComparisonJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public int $comparisonId,
        public string $prompt,
    ) {}

    public function handle(AiManager $ai): void
    {
        $comparison = ComparisonHistory::with([
            'motorcycle',
            'comparedMotorcycle',
        ])->find($this->comparisonId);

        if (! $comparison) {
            return;
        }

        $response = $ai->chat($this->prompt, [
            'motorcycle' => $comparison->motorcycle?->toArray() ?? [],
            'compared' => $comparison->comparedMotorcycle?->toArray() ?? [],
        ]);

        $comparisonData = $comparison->comparison_data ?? [];
        $comparisonData['ai_analysis'] = $response;

        $comparison->update([
            'comparison_data' => $comparisonData,
            'summary' => $response,
        ]);
    }
}
