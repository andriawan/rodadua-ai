<?php

namespace App\Repositories;

use App\Models\Workshop;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class WorkshopRepository
{
    private function cacheKey(string $key): string
    {
        return "workshop:{$key}";
    }

    /**
     * Get all workshops with filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Workshop::query();

        if (isset($filters['city'])) {
            $query->inCity($filters['city']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (isset($filters['open_weekends']) && $filters['open_weekends']) {
            $query->where('is_open_weekends', true);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get workshop by ID
     */
    public function getById(int $id): ?Workshop
    {
        return Cache::remember($this->cacheKey("detail:{$id}"), 600, function () use ($id) {
            return Workshop::find($id);
        });
    }

    /**
     * Get nearby workshops
     */
    public function getNearby(float $latitude, float $longitude, int $radiusKm = 10, int $limit = 15): array
    {
        $key = $this->cacheKey("nearby:{$latitude}:{$longitude}:{$radiusKm}");

        return Cache::remember($key, 300, function () use ($latitude, $longitude, $radiusKm, $limit) {
            return Workshop::nearBy($latitude, $longitude, $radiusKm)
                ->highRated()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Get high-rated workshops
     */
    public function getHighRated(int $limit = 10): array
    {
        return Cache::remember($this->cacheKey('high_rated'), 3600, function () use ($limit) {
            return Workshop::highRated()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Get workshops in a city
     */
    public function getByCity(string $city, int $limit = 20): array
    {
        return Cache::remember($this->cacheKey("city:{$city}"), 3600, function () use ($city, $limit) {
            return Workshop::inCity($city)
                ->highRated()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Create workshop
     */
    public function create(array $data): Workshop
    {
        $this->clearCache();

        return Workshop::create($data);
    }

    /**
     * Update workshop
     */
    public function update(Workshop $workshop, array $data): Workshop
    {
        $workshop->update($data);
        $this->clearCache();

        return $workshop;
    }

    /**
     * Update rating
     */
    public function updateRating(Workshop $workshop, float $rating, int $totalReviews): Workshop
    {
        $workshop->update([
            'rating' => $rating,
            'total_reviews' => $totalReviews,
        ]);
        $this->clearCache();

        return $workshop;
    }

    /**
     * Clear workshop cache
     */
    private function clearCache(): void
    {
        Cache::forget($this->cacheKey('high_rated'));
        // City cache keys use content-addressed keys; TTL-based expiry handles them.
    }
}
