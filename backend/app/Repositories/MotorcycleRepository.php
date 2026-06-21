<?php

namespace App\Repositories;

use App\Models\Motorcycle;
use Illuminate\Pagination\Paginator;

class MotorcycleRepository
{
    /**
     * Get all motorcycles for a user with eager loaded relationships
     */
    public function getAllByUser(int $userId, array $filters = []): Paginator
    {
        $query = Motorcycle::query()
            ->with(['maintenances', 'troubleshootingHistories'])
            ->where('user_id', $userId);

        // Apply filters
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['brand'])) {
            $query->where('brand', $filters['brand']);
        }

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get motorcycle by ID with all relationships
     *
     * Uses with() to eagerly load full relationship collections
     * for the detail view where all data is needed.
     * Uses loadMissing semantics via fresh query to guarantee
     * all relations are present.
     */
    public function getByIdWithRelations(int $id): ?Motorcycle
    {
        return Motorcycle::with([
            'user',
            'maintenances' => fn ($q) => $q->latest(),
            'troubleshootingHistories' => fn ($q) => $q->latest(),
        ])
        ->withCount(['maintenances', 'troubleshootingHistories'])
        ->find($id);
    }

    /**
     * Get favorites
     */
    public function getFavorites(int $userId): array
    {
        return Motorcycle::where('user_id', $userId)
            ->where('is_favorite', true)
            ->withCount(['maintenances', 'troubleshootingHistories'])
            ->get()
            ->toArray();
    }

    /**
     * Create motorcycle
     */
    public function create(int $userId, array $data): Motorcycle
    {
        return Motorcycle::create([
            'user_id' => $userId,
            ...$data,
        ]);
    }

    /**
     * Update motorcycle
     */
    public function update(Motorcycle $motorcycle, array $data): Motorcycle
    {
        $motorcycle->update($data);
        return $motorcycle;
    }

    /**
     * Delete motorcycle (soft delete)
     */
    public function delete(Motorcycle $motorcycle): bool
    {
        return $motorcycle->delete();
    }

    /**
     * Get motorcycles by brand
     */
    public function getByBrand(string $brand, int $limit = 10): array
    {
        return Motorcycle::where('brand', $brand)
            ->active()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get recently added motorcycles for user
     */
    public function getRecent(int $userId, int $limit = 5): array
    {
        return Motorcycle::where('user_id', $userId)
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
