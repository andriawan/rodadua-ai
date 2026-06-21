<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;

class MotorcycleTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->actingAsUser();
    }

    public function test_list_motorcycles(): void
    {
        $this->createMotorcycle($this->user);
        $this->createMotorcycle($this->user, ['brand' => 'Yamaha']);

        $response = $this->getJson('/api/v1/motorcycles');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [],
            'meta' => ['current_page', 'total'],
        ]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_create_motorcycle(): void
    {
        $response = $this->postJson('/api/v1/motorcycles', [
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'color' => 'Hitam',
            'engine_cc' => 160,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => ['brand' => 'Honda', 'model' => 'Vario 160'],
        ]);
    }

    public function test_show_motorcycle(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->getJson("/api/v1/motorcycles/{$motorcycle->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => ['id' => $motorcycle->id],
        ]);
    }

    public function test_update_motorcycle(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->putJson("/api/v1/motorcycles/{$motorcycle->id}", [
            'brand' => 'Yamaha',
            'color' => 'Merah',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => ['brand' => 'Yamaha', 'color' => 'Merah'],
        ]);
    }

    public function test_delete_motorcycle(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->deleteJson("/api/v1/motorcycles/{$motorcycle->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('motorcycles', ['id' => $motorcycle->id]);
    }

    public function test_toggle_favorite(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->postJson("/api/v1/motorcycles/{$motorcycle->id}/toggle-favorite");

        $response->assertStatus(200);
        $this->assertTrue($response->json('data.is_favorite'));
    }

    public function test_update_odometer(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        $response = $this->putJson("/api/v1/motorcycles/{$motorcycle->id}/odometer", [
            'odometer_km' => 15000,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(15000, $response->json('data.odometer_km'));
    }

    public function test_another_user_cannot_access_motorcycle(): void
    {
        $otherUser = $this->createUser();
        $motorcycle = $this->createMotorcycle($otherUser);

        $response = $this->getJson("/api/v1/motorcycles/{$motorcycle->id}");
        $response->assertStatus(403);
    }
}
