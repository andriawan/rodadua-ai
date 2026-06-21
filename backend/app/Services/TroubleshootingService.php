<?php

namespace App\Services;

use App\DTOs\TroubleshootDTO;
use App\Models\AiPrompt;
use App\Models\TroubleshootingHistory;
use App\Repositories\MotorcycleRepository;
use App\Repositories\TroubleshootingHistoryRepository;
use App\Services\Ai\AiManager;
use Illuminate\Support\Facades\DB;

class TroubleshootingService
{
    public function __construct(
        private AiManager $ai,
        private MotorcycleRepository $motorcycleRepo,
        private TroubleshootingHistoryRepository $historyRepo,
    ) {}

    /**
     * Analyze a motorcycle problem via AI and store the result.
     */
    public function analyzeProblem(TroubleshootDTO $dto, int $userId): TroubleshootingHistory
    {
        $motorcycle = $this->motorcycleRepo->getByIdWithRelations($dto->motorcycleId);

        if (! $motorcycle || $motorcycle->user_id !== $userId) {
            throw new \InvalidArgumentException('Motorcycle not found or access denied.');
        }

        // Load prompt from DB (never hardcoded)
        $prompt = $this->loadPrompt('troubleshooting.analyze', [
            'brand' => $motorcycle->brand,
            'model' => $motorcycle->model,
            'year' => $motorcycle->year,
            'odometer_km' => $motorcycle->odometer_km,
            'transmission' => $motorcycle->transmission ?? 'N/A',
            'fuel_type' => $motorcycle->fuel_type ?? 'N/A',
            'problem_description' => $dto->problemDescription,
            'symptoms' => $dto->symptoms ?? 'Tidak disebutkan',
            'maintenance_history' => $motorcycle->maintenances->isEmpty()
                ? 'Tidak ada riwayat perawatan tercatat.'
                : $motorcycle->maintenances->take(5)->map(fn ($m) => "- {$m->maintenance_date}: {$m->type} ({$m->odometer_km} km)")->implode("\n"),
        ]);

        // Call AI
        $aiResponse = $this->ai->chat($prompt, ['motorcycle' => $motorcycle->toArray()]);

        // Store result
        return $this->historyRepo->create([
            'motorcycle_id' => $dto->motorcycleId,
            'user_id' => $userId,
            'problem_description' => $dto->problemDescription,
            'symptom' => $dto->symptoms ?? '',
            'ai_analysis' => $aiResponse,
            'severity' => $this->extractSeverity($aiResponse),
            'status' => 'open',
            'ai_provider' => config('app.ai_provider', 'openai'),
        ]);
    }

    /**
     * Get AI-generated solutions for an existing troubleshooting record.
     */
    public function getSolutions(int $historyId, int $userId): array
    {
        $history = $this->historyRepo->getByMotorcycleId(
            TroubleshootingHistory::findOrFail($historyId)->motorcycle_id
        );

        return [
            'analysis' => $history->first()?->ai_analysis ?? '',
            'suggestions' => $this->parseSolutions($history->first()?->ai_analysis ?? ''),
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

    private function extractSeverity(string $analysis): string
    {
        $lower = strtolower($analysis);

        if (str_contains($lower, 'kritis') || str_contains($lower, 'critical')) {
            return 'critical';
        }
        if (str_contains($lower, 'tinggi') || str_contains($lower, 'high')) {
            return 'high';
        }
        if (str_contains($lower, 'sedang') || str_contains($lower, 'medium')) {
            return 'medium';
        }

        return 'low';
    }

    private function parseSolutions(string $analysis): array
    {
        $lines = explode("\n", $analysis);
        $solutions = [];

        $inSolutions = false;
        foreach ($lines as $line) {
            if (preg_match('/solusi|langkah|perbaikan/i', $line) && preg_match('/^\d+\.|^\*|^-/', trim($line))) {
                $inSolutions = true;
            }
            if ($inSolutions && trim($line)) {
                if (preg_match('/^\d+\.|^\*|^-/', trim($line))) {
                    $solutions[] = trim(preg_replace('/^\d+\.\s*|\*\s*|-\s*/', '', $line));
                } elseif (preg_match('/^[A-Z]/', trim($line)) && $inSolutions) {
                    $inSolutions = false;
                }
            }
        }

        return $solutions ?: [substr($analysis, 0, 200)];
    }
}
