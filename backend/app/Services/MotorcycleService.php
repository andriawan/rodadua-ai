<?php

namespace App\Services;

use App\DTOs\CreateMotorcycleDTO;
use App\DTOs\UpdateMotorcycleDTO;
use App\Models\Motorcycle;
use App\Repositories\MotorcycleRepository;

class MotorcycleService
{
    public function __construct(private MotorcycleRepository $repository) {}

    /**
     * Get all motorcycles for user
     */
    public function getAllByUser(int $userId, array $filters = [])
    {
        return $this->repository->getAllByUser($userId, $filters);
    }

    /**
     * Get motorcycle with all data
     */
    public function getById(int $motorcycleId): ?Motorcycle
    {
        return $this->repository->getByIdWithRelations($motorcycleId);
    }

    /**
     * Create new motorcycle
     */
    public function create(int $userId, CreateMotorcycleDTO $dto): Motorcycle
    {
        return $this->repository->create($userId, $dto->toArray());
    }

    /**
     * Update motorcycle
     */
    public function update(Motorcycle $motorcycle, UpdateMotorcycleDTO $dto): Motorcycle
    {
        return $this->repository->update($motorcycle, $dto->toArray());
    }

    /**
     * Delete motorcycle
     */
    public function delete(Motorcycle $motorcycle): bool
    {
        return $this->repository->delete($motorcycle);
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Motorcycle $motorcycle): Motorcycle
    {
        return $this->repository->update($motorcycle, [
            'is_favorite' => ! $motorcycle->is_favorite,
        ]);
    }

    /**
     * Update odometer reading
     */
    public function updateOdometer(Motorcycle $motorcycle, int $kilometers): Motorcycle
    {
        return $this->repository->update($motorcycle, [
            'odometer_km' => $kilometers,
        ]);
    }

    /**
     * Get motorcycles by brand
     */
    public function getByBrand(string $brand): array
    {
        return $this->repository->getByBrand($brand);
    }

    /**
     * Get recently added motorcycles
     */
    public function getRecent(int $userId): array
    {
        return $this->repository->getRecent($userId);
    }
}
