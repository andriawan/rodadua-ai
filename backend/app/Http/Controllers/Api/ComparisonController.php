<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComparisonHistory;
use App\Services\AiLogService;
use App\Services\ComparisonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function __construct(
        private ComparisonService $service,
        private AiLogService $logService,
    ) {}

    /**
     * Compare two motorcycles.
     */
    public function compare(int $motorcycleId, int $comparedMotorcycleId, Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $result = $this->service->compare(
                $motorcycleId,
                $comparedMotorcycleId,
                $user->id
            );

            $this->logService->log([
                'user_id' => $user->id,
                'provider' => config('app.ai_provider', 'openai'),
                'prompt_key' => 'comparison.compare',
                'request_data' => [
                    'motorcycle_id' => $motorcycleId,
                    'compared_id' => $comparedMotorcycleId,
                ],
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $result->id,
                    'motorcycle_id' => $result->motorcycle_id,
                    'compared_motorcycle_id' => $result->compared_motorcycle_id,
                    'comparison_data' => $result->comparison_data,
                    'summary' => $result->summary,
                    'created_at' => $result->created_at,
                ],
            ]);
        } catch (\Exception $e) {
            $this->logService->logError(
                config('app.ai_provider', 'openai'),
                $e->getMessage(),
                $user->id,
                'comparison.compare'
            );

            return response()->json([
                'success' => false,
                'message' => 'Gagal membandingkan motor.',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Get comparison history for a motorcycle.
     */
    public function history(int $motorcycleId, Request $request): JsonResponse
    {
        $histories = ComparisonHistory::where('motorcycle_id', $motorcycleId)
            ->with(['comparedMotorcycle:id,brand,model,year'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories,
        ]);
    }
}
