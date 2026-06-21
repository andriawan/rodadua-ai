<?php

namespace Tests\Unit\Requests;

use App\Models\Motorcycle;
use App\Models\User;
use Tests\TestCase;

class FormRequestTest extends TestCase
{
    // === StoreUserRequest ===

    public function test_store_user_validates_required_name(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_store_user_validates_valid_email(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_store_user_validates_password_min_length(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_store_user_validates_password_confirmed(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    // === LoginRequest ===

    public function test_login_validates_required_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'password' => 'secret',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_login_validates_required_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    // === StoreMotorcycleRequest ===

    public function test_store_motorcycle_validates_required_brand(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/motorcycles', [
            'model' => 'Vario 160',
            'year' => 2024,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['brand']);
    }

    public function test_store_motorcycle_validates_year_range(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/motorcycles', [
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 1800,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year']);
    }

    public function test_store_motorcycle_validates_transmission_value(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/motorcycles', [
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'transmission' => 'invalid',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['transmission']);
    }

    // === StoreTroubleshootRequest ===

    public function test_store_troubleshoot_validates_required_fields(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/troubleshooting/analyze', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['motorcycle_id', 'problem_description']);
    }

    public function test_store_troubleshoot_validates_motorcycle_exists(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/troubleshooting/analyze', [
            'motorcycle_id' => 999,
            'problem_description' => 'Test problem',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['motorcycle_id']);
    }

    // === StoreMaintenanceRequest ===

    public function test_store_maintenance_validates_required_fields(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/maintenance', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['motorcycle_id', 'type', 'odometer_km', 'maintenance_date']);
    }

    public function test_store_maintenance_validates_status_value(): void
    {
        $user = $this->actingAsUser();

        $response = $this->postJson('/api/v1/maintenance', [
            'motorcycle_id' => 1,
            'type' => 'Ganti Oli',
            'odometer_km' => 5000,
            'maintenance_date' => '2024-01-15',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    // === UpdateMotorcycleRequest (authorization) ===

    public function test_update_motorcycle_authorization_denies_other_users(): void
    {
        $owner = $this->createUser();
        $other = $this->actingAsUser();
        $motorcycle = $this->createMotorcycle($owner);

        $response = $this->putJson("/api/v1/motorcycles/{$motorcycle->id}", [
            'brand' => 'Updated',
        ]);

        $response->assertStatus(403);
    }
}
