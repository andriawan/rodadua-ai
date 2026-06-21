<?php

namespace App\Repositories;

use App\Models\Maintenance;
use Illuminate\Pagination\LengthAwarePaginator;

class MaintenanceRepository
{
    /**
     * Get all maintenances for a motorcycle
     */
    public function getByMotorcycleId(int $motorcycleId, array $filters = []): LengthAwarePaginator
    {
        $query = Maintenance::query()
            ->with(['motorcycle', 'user'])
            ->where('motorcycle_id', $motorcycleId);

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        return $query->latest('maintenance_date')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get upcoming maintenances
     */
    public function getUpcoming(int $motorcycleId, int $limit = 10): array
    {
        return Maintenance::where('motorcycle_id', $motorcycleId)
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->with(['motorcycle'])
            ->latest('next_maintenance_date')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get maintenance history by user
     */
    public function getByUserId(int $userId, int $limit = 20): array
    {
        return Maintenance::query()
            ->where('user_id', $userId)
            ->with(['motorcycle', 'user'])
            ->completed()
            ->latest('maintenance_date')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Create maintenance record
     */
    public function create(array $data): Maintenance
    {
        return Maintenance::create($data);
    }

    /**
     * Update maintenance record
     */
    public function update(Maintenance $maintenance, array $data): Maintenance
    {
        $maintenance->update($data);
        return $maintenance;
    }

    /**
     * Delete maintenance record
     */
    public function delete(Maintenance $maintenance): bool
    {
        return $maintenance->delete();
    }
}
