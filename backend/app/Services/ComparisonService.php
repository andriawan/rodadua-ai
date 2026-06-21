<?php

namespace App\Services;

use App\Models\AiPrompt;
use App\Models\ComparisonHistory;
use App\Repositories\MotorcycleRepository;
use App\Services\Ai\AiManager;

class ComparisonService
{
    public function __construct(
        private AiManager $ai,
        private MotorcycleRepository $motorcycleRepo,
    ) {}

    /**
     * Compare two motorcycles via AI and store the result.
     */
    public function compare(int $motorcycleId, int $comparedId, int $userId): ComparisonHistory
    {
        $motorcycle = $this->motorcycleRepo->getByIdWithRelations($motorcycleId);
        $compared = $this->motorcycleRepo->getByIdWithRelations($comparedId);

        if (! $motorcycle || $compared->user_id !== $userId) {
            throw new \InvalidArgumentException('Motorcycle not found or access denied.');
        }

        if (! $compared) {
            throw new \InvalidArgumentException('Comparison motorcycle not found.');
        }

        $prompt = $this->loadPrompt('comparison.compare', [
            'brand1' => $motorcycle->brand,
            'model1' => $motorcycle->model,
            'year1' => $motorcycle->year,
            'engine_cc1' => $motorcycle->engine_cc ?? 'N/A',
            'transmission1' => $motorcycle->transmission ?? 'N/A',
            'odometer_km1' => $motorcycle->odometer_km,
            'fuel_type1' => $motorcycle->fuel_type ?? 'N/A',
            'brand2' => $compared->brand,
            'model2' => $compared->model,
            'year2' => $compared->year,
            'engine_cc2' => $compared->engine_cc ?? 'N/A',
            'transmission2' => $compared->transmission ?? 'N/A',
            'odometer_km2' => $compared->odometer_km,
            'fuel_type2' => $compared->fuel_type ?? 'N/A',
        ]);

        $response = $this->ai->chat($prompt, [
            'motorcycle' => $motorcycle->toArray(),
            'compared' => $compared->toArray(),
        ]);

        // Extract structured data from AI response and store
        $comparisonData = [
            'motorcycle' => [
                'id' => $motorcycle->id,
                'brand' => $motorcycle->brand,
                'model' => $motorcycle->model,
                'year' => $motorcycle->year,
                'engine_cc' => $motorcycle->engine_cc,
                'transmission' => $motorcycle->transmission,
            ],
            'compared' => [
                'id' => $compared->id,
                'brand' => $compared->brand,
                'model' => $compared->model,
                'year' => $compared->year,
                'engine_cc' => $compared->engine_cc,
                'transmission' => $compared->transmission,
            ],
            'ai_analysis' => $response,
        ];

        return ComparisonHistory::create([
            'user_id' => $userId,
            'motorcycle_id' => $motorcycleId,
            'compared_motorcycle_id' => $comparedId,
            'comparison_data' => $comparisonData,
            'summary' => $response,
        ]);
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
