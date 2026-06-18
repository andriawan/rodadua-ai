<?php

namespace App\Repositories;

use App\Models\Workshop;
use Illuminate\Pagination\Paginator;

class WorkshopRepository
{
    /**
     * Get all workshops with filters
     */
    public function getAll(array $filters = []): Paginator
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
        return Workshop::find($id);
    }

    /**
     * Get nearby workshops
     */
    public function getNearby(float $latitude, float $longitude, int $radiusKm = 10, int $limit = 15): array
    {
        return Workshop::nearBy($latitude, $longitude, $radiusKm)
            ->highRated()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get high-rated workshops
     */
    public function getHighRated(int $limit = 10): array
    {
        return Workshop::highRated()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get workshops in a city
     */
    public function getByCity(string $city, int $limit = 20): array
    {
        return Workshop::inCity($city)
            ->highRated()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Create workshop
     */
    public function create(array $data): Workshop
    {
        return Workshop::create($data);
    }

    /**
     * Update workshop
     */
    public function update(Workshop $workshop, array $data): Workshop
    {
        $workshop->update($data);
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
        return $workshop;
    }
}
