<?php

namespace Tests\Unit\Repositories;

use App\Models\Maintenance;
use App\Models\Motorcycle;
use App\Models\TroubleshootingHistory;
use App\Models\User;
use App\Models\Workshop;
use App\Repositories\MaintenanceRepository;
use App\Repositories\MotorcycleRepository;
use App\Repositories\TroubleshootingHistoryRepository;
use App\Repositories\WorkshopRepository;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_motorcycle_repository_get_all_by_user(): void
    {
        $this->createMotorcycle($this->user);
        $this->createMotorcycle($this->user);
        $this->createMotorcycle($this->user, ['brand' => 'Yamaha']);

        $repo = app(MotorcycleRepository::class);
        $result = $repo->getAllByUser($this->user->id);

        $this->assertCount(3, $result->items());
    }

    public function test_motorcycle_repository_get_by_id_with_relations(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $repo = app(MotorcycleRepository::class);
        $result = $repo->getByIdWithRelations($motorcycle->id);

        $this->assertNotNull($result);
        $this->assertSame($motorcycle->id, $result->id);
        $this->assertTrue($result->relationLoaded('maintenances'));
    }

    public function test_motorcycle_repository_get_recent(): void
    {
        $this->createMotorcycle($this->user);
        $this->createMotorcycle($this->user);

        $repo = app(MotorcycleRepository::class);
        $result = $repo->getRecent($this->user->id);

        $this->assertCount(2, $result);
    }

    public function test_motorcycle_repository_get_by_brand(): void
    {
        Motorcycle::factory()->active()->create(['user_id' => $this->user->id, 'brand' => 'Honda']);
        Motorcycle::factory()->active()->create(['user_id' => $this->user->id, 'brand' => 'Honda']);
        Motorcycle::factory()->active()->create(['user_id' => $this->user->id, 'brand' => 'Yamaha']);

        $repo = app(MotorcycleRepository::class);
        $result = $repo->getByBrand('Honda');

        $this->assertCount(2, $result);
    }

    public function test_motorcycle_repository_get_favorites(): void
    {
        $this->createMotorcycle($this->user, ['is_favorite' => true]);
        $this->createMotorcycle($this->user, ['is_favorite' => false]);

        $repo = app(MotorcycleRepository::class);
        $result = $repo->getFavorites($this->user->id);

        $this->assertCount(1, $result);
    }

    public function test_maintenance_repository_get_by_motorcycle_id(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);
        Maintenance::factory(3)->create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => $this->user->id,
        ]);

        $repo = app(MaintenanceRepository::class);
        $result = $repo->getByMotorcycleId($motorcycle->id);

        $this->assertCount(3, $result->items());
    }

    public function test_maintenance_repository_get_upcoming(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);
        Maintenance::factory()->completed()->create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => $this->user->id,
            'next_maintenance_date' => now()->addMonth()->toDateString(),
        ]);

        $repo = app(MaintenanceRepository::class);
        $result = $repo->getUpcoming($motorcycle->id);

        $this->assertCount(1, $result);
    }

    public function test_troubleshooting_repository_get_by_motorcycle_id(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);
        TroubleshootingHistory::factory(2)->create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => $this->user->id,
        ]);

        $repo = app(TroubleshootingHistoryRepository::class);
        $result = $repo->getByMotorcycleId($motorcycle->id);

        $this->assertCount(2, $result->items());
    }

    public function test_workshop_repository_get_nearby(): void
    {
        Workshop::factory()->create([
            'rating' => 4.5,
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $repo = app(WorkshopRepository::class);
        $result = $repo->getNearby(-6.2, 106.8, 50);

        $this->assertNotEmpty($result);
    }

    public function test_workshop_repository_get_high_rated(): void
    {
        Workshop::factory(3)->highRated()->create();
        Workshop::factory(2)->create(['rating' => 2.0]);

        $repo = app(WorkshopRepository::class);
        $result = $repo->getHighRated();

        $this->assertCount(3, $result);
    }

    public function test_workshop_repository_get_by_city(): void
    {
        Workshop::factory(2)->create(['city' => 'Jakarta', 'rating' => 4.5]);
        Workshop::factory()->create(['city' => 'Bandung', 'rating' => 4.5]);

        $repo = app(WorkshopRepository::class);
        $result = $repo->getByCity('Jakarta');

        $this->assertCount(2, $result);
    }
}
