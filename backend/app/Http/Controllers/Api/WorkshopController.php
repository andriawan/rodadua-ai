<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    /**
     * Search workshops with filters.
     */
    public function search(Request $request): JsonResponse
    {
        $query = Workshop::query();

        if ($request->filled('city')) {
            $query->inCity($request->city);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->boolean('open_weekends')) {
            $query->where('is_open_weekends', true);
        }

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $radius = $request->integer('radius', 10);
            $query->nearby(
                (float) $request->latitude,
                (float) $request->longitude,
                $radius
            );
        }

        $workshops = $query->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $workshops->items(),
            'meta' => [
                'current_page' => $workshops->currentPage(),
                'per_page' => $workshops->perPage(),
                'total' => $workshops->total(),
                'last_page' => $workshops->lastPage(),
            ],
        ]);
    }

    /**
     * Get workshop detail.
     */
    public function show(int $id): JsonResponse
    {
        $workshop = Workshop::find($id);

        if (! $workshop) {
            return response()->json([
                'success' => false,
                'message' => 'Bengkel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $workshop,
        ]);
    }
}
