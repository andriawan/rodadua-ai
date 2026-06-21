<?php

namespace App\Repositories;

use App\Models\Motorcycle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class MotorcycleRepository
{
    private function cacheKey(string $key): string
    {
        return "motorcycle:{$key}";
    }

    /**
     * Get all motorcycles for a user with eager loaded relationships
     */
    public function getAllByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Motorcycle::query()
            ->with(['maintenances', 'troubleshootingHistories'])
            ->where('user_id', $userId);

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
     */
    public function getByIdWithRelations(int $id): ?Motorcycle
    {
        return Cache::remember($this->cacheKey("detail:{$id}"), 600, function () use ($id) {
            return Motorcycle::with([
                'user',
                'maintenances' => fn ($q) => $q->latest(),
                'troubleshootingHistories' => fn ($q) => $q->latest(),
            ])
            ->withCount(['maintenances', 'troubleshootingHistories'])
            ->find($id);
        });
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
        Cache::forget($this->cacheKey("detail:{$motorcycle->id}"));

        return $motorcycle;
    }

    /**
     * Delete motorcycle (soft delete)
     */
    public function delete(Motorcycle $motorcycle): bool
    {
        $result = $motorcycle->delete();
        Cache::forget($this->cacheKey("detail:{$motorcycle->id}"));

        return $result;
    }

    /**
     * Get motorcycles by brand
     */
    public function getByBrand(string $brand, int $limit = 10): array
    {
        return Cache::remember($this->cacheKey("brand:{$brand}"), 3600, function () use ($brand, $limit) {
            return Motorcycle::where('brand', $brand)
                ->active()
                ->limit($limit)
                ->get()
                ->toArray();
        });
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
