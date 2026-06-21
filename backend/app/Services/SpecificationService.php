<?php

namespace App\Services;

use App\Models\AiPrompt;
use App\Services\Ai\AiManager;

class SpecificationService
{
    public function __construct(
        private AiManager $ai,
    ) {}

    /**
     * Extract structured specifications from a free-text description.
     */
    public function extractSpecs(string $description): array
    {
        $prompt = $this->loadPrompt('specification.extract', [
            'description' => $description,
        ]);

        return $this->ai->generateStructured($prompt, [
            'brand', 'model', 'year', 'engine_cc', 'engine_type', 'transmission', 'fuel_type', 'color',
        ]);
    }

    /**
     * Validate that specifications are internally consistent.
     */
    public function validateSpecs(array $specs): array
    {
        $prompt = $this->loadPrompt('specification.validate', [
            'brand' => $specs['brand'] ?? 'Unknown',
            'model' => $specs['model'] ?? 'Unknown',
            'year' => $specs['year'] ?? 'Unknown',
            'engine_cc' => $specs['engine_cc'] ?? 'Unknown',
            'engine_type' => $specs['engine_type'] ?? 'Unknown',
            'transmission' => $specs['transmission'] ?? 'Unknown',
            'fuel_type' => $specs['fuel_type'] ?? 'Unknown',
        ]);

        $response = $this->ai->chat($prompt, ['specs' => $specs]);

        return [
            'specs' => $specs,
            'validation' => $response,
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
