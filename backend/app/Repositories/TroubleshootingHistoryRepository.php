<?php

namespace App\Repositories;

use App\Models\TroubleshootingHistory;
use Illuminate\Pagination\LengthAwarePaginator;

class TroubleshootingHistoryRepository
{
    /**
     * Get troubleshooting history for a motorcycle
     */
    public function getByMotorcycleId(int $motorcycleId, array $filters = []): LengthAwarePaginator
    {
        $query = TroubleshootingHistory::query()
            ->with(['motorcycle', 'user'])
            ->where('motorcycle_id', $motorcycleId);

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['severity']) && $filters['severity'] !== 'all') {
            $query->where('severity', $filters['severity']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get troubleshooting history by user
     */
    public function getByUserId(int $userId, int $limit = 20): array
    {
        return TroubleshootingHistory::where('user_id', $userId)
            ->with(['motorcycle'])
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get recent open issues for a motorcycle
     */
    public function getOpenIssues(int $motorcycleId, int $limit = 5): array
    {
        return TroubleshootingHistory::where('motorcycle_id', $motorcycleId)
            ->open()
            ->with(['motorcycle'])
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Create troubleshooting record
     */
    public function create(array $data): TroubleshootingHistory
    {
        return TroubleshootingHistory::create($data);
    }

    /**
     * Update troubleshooting record
     */
    public function update(TroubleshootingHistory $history, array $data): TroubleshootingHistory
    {
        $history->update($data);

        return $history;
    }

    /**
     * Delete troubleshooting record (soft delete)
     */
    public function delete(TroubleshootingHistory $history): bool
    {
        return $history->delete();
    }
}
