<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\Ai\AiManager;
use Tests\TestCase;

class AiEndpointsTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsUser();

        // Mock AiManager to return controlled responses
        $aiMock = \Mockery::mock(AiManager::class);
        $aiMock->shouldReceive('chat')
            ->andReturn('**Diagnosa Awal**: Kemungkinan aki lemah. **Solusi**: Coba cas aki terlebih dahulu.');
        app()->instance(AiManager::class, $aiMock);
    }

    public function test_troubleshooting_analyze(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->postJson('/api/v1/troubleshooting/analyze', [
            'motorcycle_id' => $motorcycle->id,
            'problem_description' => 'Motor tidak bisa distarter',
            'symptoms' => 'Suara klik saat starter ditekan',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'motorcycle_id', 'ai_analysis', 'severity', 'status'],
        ]);
    }

    public function test_troubleshooting_history(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/troubleshooting/{$motorcycle->id}/history");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'meta' => ['current_page', 'total'],
        ]);
    }

    public function test_maintenance_recommendations(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/maintenance/recommendations/{$motorcycle->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['motorcycle', 'recommendations', 'provider'],
        ]);
    }

    public function test_maintenance_predict(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/maintenance/predict/{$motorcycle->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['motorcycle', 'prediction', 'history_count'],
        ]);
    }

    public function test_maintenance_store(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->postJson('/api/v1/maintenance', [
            'motorcycle_id' => $motorcycle->id,
            'type' => 'Ganti Oli',
            'description' => 'Ganti oli mesin rutin',
            'odometer_km' => 5000,
            'maintenance_date' => '2024-01-15',
            'cost' => 150000,
            'workshop' => 'Bengkel ABC',
            'status' => 'completed',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('maintenances', [
            'motorcycle_id' => $motorcycle->id,
            'type' => 'Ganti Oli',
        ]);
    }

    public function test_comparison_compare(): void
    {
        $motorcycleA = $this->createMotorcycle($this->user);
        $motorcycleB = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/motorcycles/{$motorcycleA->id}/compare/{$motorcycleB->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'motorcycle_id', 'compared_motorcycle_id', 'summary'],
        ]);
    }

    public function test_comparison_history(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/comparisons/{$motorcycle->id}/history");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_workshop_search_by_city(): void
    {
        \App\Models\Workshop::factory(2)->create(['city' => 'Jakarta', 'rating' => 4.5]);

        $response = $this->getJson('/api/v1/workshops/search?city=Jakarta');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'meta' => ['current_page', 'total'],
        ]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_workshop_show(): void
    {
        $workshop = \App\Models\Workshop::factory()->create();

        $response = $this->getJson("/api/v1/workshops/{$workshop->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => ['id' => $workshop->id],
        ]);
    }

    public function test_workshop_show_not_found(): void
    {
        $response = $this->getJson('/api/v1/workshops/999');

        $response->assertStatus(404);
    }
}
