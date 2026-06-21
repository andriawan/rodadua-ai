<?php

namespace App\Repositories;

use App\Models\SparePart;
use Illuminate\Pagination\LengthAwarePaginator;

class SparePartRepository
{
    /**
     * Get all spare parts with filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = SparePart::query();

        if (isset($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['brand'])) {
            $query->byBrand($filters['brand']);
        }

        if (isset($filters['in_stock'])) {
            $query->inStock();
        }

        if (isset($filters['search'])) {
            $query->searchByPartNumber($filters['search']);
        }

        if (isset($filters['compatible_model'])) {
            $query->whereJsonContains('compatible_motorcycles', $filters['compatible_model']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get spare part by ID
     */
    public function getById(int $id): ?SparePart
    {
        return SparePart::find($id);
    }

    /**
     * Incerment view count
     */
    public function incrementViewCount(SparePart $sparePart): void
    {
        $sparePart->increment('view_count');
    }

    /**
     * Create spare part
     */
    public function create(array $data): SparePart
    {
        return SparePart::create($data);
    }

    /**
     * Update spare part
     */
    public function update(SparePart $sparePart, array $data): SparePart
    {
        $sparePart->update($data);

        return $sparePart;
    }

    /**
     * Delete spare part (soft delete)
     */
    public function delete(SparePart $sparePart): bool
    {
        return $sparePart->delete();
    }
}
