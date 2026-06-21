<?php

namespace App\Jobs;

use App\Models\Motorcycle;
use App\Services\Ai\AiManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMaintenanceJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public int $motorcycleId,
        public string $prompt,
    ) {}

    public function handle(AiManager $ai): void
    {
        $motorcycle = Motorcycle::with(['maintenances'])->find($this->motorcycleId);

        if (! $motorcycle) {
            return;
        }

        $response = $ai->chat($this->prompt, [
            'motorcycle' => $motorcycle->toArray(),
        ]);

        // Store result in cache for the controller to pick up
        cache()->put(
            "maintenance_recommendations_{$this->motorcycleId}",
            $response,
            now()->addHours(24)
        );
    }
}
