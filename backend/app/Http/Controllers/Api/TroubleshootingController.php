<?php

namespace App\Http\Controllers\Api;

use App\DTOs\TroubleshootDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTroubleshootRequest;
use App\Services\AiLogService;
use App\Services\TroubleshootingService;
use Illuminate\Http\JsonResponse;

class TroubleshootingController extends Controller
{
    public function __construct(
        private TroubleshootingService $service,
        private AiLogService $logService,
    ) {}

    /**
     * Analyze a motorcycle problem via AI.
     */
    public function analyze(StoreTroubleshootRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $user = $request->user();

        try {
            $result = $this->service->analyzeProblem(
                TroubleshootDTO::fromRequest($request),
                $user->id
            );

            $duration = (int) ((microtime(true) - $startTime) * 1000);

            $this->logService->log([
                'user_id' => $user->id,
                'provider' => config('app.ai_provider', 'openai'),
                'prompt_key' => 'troubleshooting.analyze',
                'request_data' => $request->validated(),
                'duration_ms' => $duration,
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $result->id,
                    'motorcycle_id' => $result->motorcycle_id,
                    'problem_description' => $result->problem_description,
                    'ai_analysis' => $result->ai_analysis,
                    'severity' => $result->severity,
                    'status' => $result->status,
                    'created_at' => $result->created_at,
                ],
            ], 201);
        } catch (\Exception $e) {
            $this->logService->logError(
                config('app.ai_provider', 'openai'),
                $e->getMessage(),
                $user->id,
                'troubleshooting.analyze'
            );

            return response()->json([
                'success' => false,
                'message' => 'Gagal menganalisis masalah motor.',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Get troubleshooting history for a motorcycle.
     */
    public function history(int $motorcycleId, \Illuminate\Http\Request $request): JsonResponse
    {
        $user = $request->user();

        $history = \App\Models\TroubleshootingHistory::where('motorcycle_id', $motorcycleId)
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $history->items(),
            'meta' => [
                'current_page' => $history->currentPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
                'last_page' => $history->lastPage(),
            ],
        ]);
    }
}
