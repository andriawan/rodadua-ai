<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateMotorcycleDTO;
use App\DTOs\UpdateMotorcycleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMotorcycleRequest;
use App\Http\Requests\UpdateMotorcycleRequest;
use App\Http\Resources\MotorcycleResource;
use App\Models\Motorcycle;
use App\Services\MotorcycleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function __construct(private MotorcycleService $service) {}

    /**
     * Get all motorcycles for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'brand', 'year', 'search', 'per_page']);
        $motorcycles = $this->service->getAllByUser($request->user()->id, $filters);

        return response()->json([
            'success' => true,
            'data' => MotorcycleResource::collection($motorcycles),
            'meta' => [
                'current_page' => $motorcycles->currentPage(),
                'per_page' => $motorcycles->perPage(),
                'total' => $motorcycles->total(),
                'last_page' => $motorcycles->lastPage(),
            ],
        ], 200);
    }

    /**
     * Get single motorcycle
     */
    public function show(Request $request, Motorcycle $motorcycle): JsonResponse
    {
        $this->authorize('view', $motorcycle);

        $motorcycle = $this->service->getById($motorcycle->id);

        return response()->json([
            'success' => true,
            'data' => new MotorcycleResource($motorcycle),
        ], 200);
    }

    /**
     * Create new motorcycle
     */
    public function store(StoreMotorcycleRequest $request): JsonResponse
    {
        try {
            $motorcycle = $this->service->create(
                $request->user()->id,
                CreateMotorcycleDTO::fromRequest($request)
            );

            return response()->json([
                'success' => true,
                'data' => new MotorcycleResource($motorcycle),
                'message' => 'Motorcycle created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create motorcycle',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Update motorcycle
     */
    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle): JsonResponse
    {
        $this->authorize('update', $motorcycle);

        try {
            $updated = $this->service->update(
                $motorcycle,
                UpdateMotorcycleDTO::fromRequest($request)
            );

            return response()->json([
                'success' => true,
                'data' => new MotorcycleResource($updated),
                'message' => 'Motorcycle updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update motorcycle',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Delete motorcycle
     */
    public function destroy(Request $request, Motorcycle $motorcycle): JsonResponse
    {
        $this->authorize('delete', $motorcycle);

        try {
            $this->service->delete($motorcycle);

            return response()->json([
                'success' => true,
                'message' => 'Motorcycle deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete motorcycle',
                'errors' => ['error' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Request $request, Motorcycle $motorcycle): JsonResponse
    {
        $this->authorize('update', $motorcycle);

        $updated = $this->service->toggleFavorite($motorcycle);

        return response()->json([
            'success' => true,
            'data' => new MotorcycleResource($updated),
            'message' => 'Favorite status updated',
        ], 200);
    }

    /**
     * Update odometer reading
     */
    public function updateOdometer(Request $request, Motorcycle $motorcycle): JsonResponse
    {
        $this->authorize('update', $motorcycle);

        $request->validate([
            'odometer_km' => ['required', 'integer', 'min:0'],
        ]);

        $updated = $this->service->updateOdometer(
            $motorcycle,
            $request->integer('odometer_km')
        );

        return response()->json([
            'success' => true,
            'data' => new MotorcycleResource($updated),
            'message' => 'Odometer updated successfully',
        ], 200);
    }
}
