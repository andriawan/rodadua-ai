<?php

namespace App\Services;

use App\Models\AiPrompt;
use App\Models\Motorcycle;
use App\Repositories\MaintenanceRepository;
use App\Repositories\MotorcycleRepository;
use App\Services\Ai\AiManager;

class MaintenanceService
{
    public function __construct(
        private AiManager $ai,
        private MotorcycleRepository $motorcycleRepo,
        private MaintenanceRepository $maintenanceRepo,
    ) {}

    /**
     * Get AI-generated maintenance recommendations for a motorcycle.
     */
    public function getRecommendations(int $motorcycleId, int $userId): array
    {
        $motorcycle = $this->motorcycleRepo->getByIdWithRelations($motorcycleId);

        if (! $motorcycle || $motorcycle->user_id !== $userId) {
            throw new \InvalidArgumentException('Motorcycle not found or access denied.');
        }

        $prompt = $this->loadPrompt('maintenance.recommend', [
            'brand' => $motorcycle->brand,
            'model' => $motorcycle->model,
            'year' => $motorcycle->year,
            'odometer_km' => $motorcycle->odometer_km,
            'transmission' => $motorcycle->transmission ?? 'N/A',
            'fuel_type' => $motorcycle->fuel_type ?? 'N/A',
        ]);

        $response = $this->ai->chat($prompt, ['motorcycle' => $motorcycle->toArray()]);

        return [
            'motorcycle' => [
                'id' => $motorcycle->id,
                'brand' => $motorcycle->brand,
                'model' => $motorcycle->model,
                'year' => $motorcycle->year,
                'odometer_km' => $motorcycle->odometer_km,
            ],
            'recommendations' => $response,
            'provider' => config('app.ai_provider', 'openai'),
        ];
    }

    /**
     * Predict next maintenance based on history.
     */
    public function predictNextMaintenance(int $motorcycleId, int $userId): array
    {
        $motorcycle = $this->motorcycleRepo->getByIdWithRelations($motorcycleId);

        if (! $motorcycle || $motorcycle->user_id !== $userId) {
            throw new \InvalidArgumentException('Motorcycle not found or access denied.');
        }

        $history = $motorcycle->maintenances->map(fn ($m) => [
            'date' => $m->maintenance_date?->format('Y-m-d'),
            'type' => $m->type,
            'odometer' => $m->odometer_km,
            'cost' => $m->cost,
        ])->toArray();

        $prompt = $this->loadPrompt('maintenance.predict', [
            'brand' => $motorcycle->brand,
            'model' => $motorcycle->model,
            'year' => $motorcycle->year,
            'odometer_km' => $motorcycle->odometer_km,
            'maintenance_history' => empty($history)
                ? 'Belum ada riwayat perawatan.'
                : collect($history)->map(fn ($h) => "- {$h['date']}: {$h['type']} ({$h['odometer']} km, Rp {$h['cost']})")->implode("\n"),
        ]);

        $response = $this->ai->chat($prompt, ['motorcycle' => $motorcycle->toArray()]);

        return [
            'motorcycle' => [
                'id' => $motorcycle->id,
                'brand' => $motorcycle->brand,
                'model' => $motorcycle->model,
                'odometer_km' => $motorcycle->odometer_km,
            ],
            'prediction' => $response,
            'history_count' => count($history),
        ];
    }

    private function loadPrompt(string $key, array $placeholders): string
    {
        $prompt = AiPrompt::active()->byKey($key)->first();

        if (! $prompt) {
            throw new \RuntimeException("AI prompt '{$key}' not found. Run database seeders.");
        }

        $content = $prompt->content;

        foreach ($placeholders as $key => $value) {
            $content = str_replace("{{{$key}}}", (string) $value, $content);
        }

        return $content;
    }
}
