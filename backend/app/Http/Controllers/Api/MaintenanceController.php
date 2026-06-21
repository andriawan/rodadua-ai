<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Models\Maintenance;
use App\Services\AiLogService;
use App\Services\MaintenanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct(
        private MaintenanceService $service,
        private AiLogService $logService,
    ) {}

    /**
     * Get AI-powered maintenance recommendations.
     */
    public function recommendations(int $motorcycleId, Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $result = $this->service->getRecommendations($motorcycleId, $user->id);

            $this->logService->log([
                'user_id' => $user->id,
                'provider' => config('app.ai_provider', 'openai'),
                'prompt_key' => 'maintenance.recommend',
                'request_data' => ['motorcycle_id' => $motorcycleId],
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logService->logError(
                config('app.ai_provider', 'openai'),
                $e->getMessage(),
                $user->id,
                'maintenance.recommend'
            );

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan rekomendasi perawatan.',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Predict next maintenance.
     */
    public function predict(int $motorcycleId, Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $result = $this->service->predictNextMaintenance($motorcycleId, $user->id);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memprediksi perawatan selanjutnya.',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Log a new maintenance record.
     */
    public function store(StoreMaintenanceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $maintenance = Maintenance::create($data);

        return response()->json([
            'success' => true,
            'data' => $maintenance,
            'message' => 'Catatan perawatan berhasil disimpan.',
        ], 201);
    }
}
