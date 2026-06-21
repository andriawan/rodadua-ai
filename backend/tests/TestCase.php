<?php

namespace Tests;

use App\Models\Motorcycle;
use App\Models\User;
use Database\Seeders\AiPromptSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run all migrations once per test class via RefreshDatabase trait,
        // then seed AI prompts (needed for AI service tests).
        $this->seed(AiPromptSeeder::class);
    }

    protected function createUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role' => User::ROLE_USER,
        ], $overrides));
    }

    protected function createAdmin(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role' => User::ROLE_ADMIN,
        ], $overrides));
    }

    protected function actingAsUser(array $overrides = []): User
    {
        $user = $this->createUser($overrides);
        Sanctum::actingAs($user);

        return $user;
    }

    protected function actingAsAdmin(array $overrides = []): User
    {
        $admin = $this->createAdmin($overrides);
        Sanctum::actingAs($admin);

        return $admin;
    }

    protected function createMotorcycle(User $user, array $overrides = []): Motorcycle
    {
        return Motorcycle::factory()->create(array_merge([
            'user_id' => $user->id,
        ], $overrides));
    }
}
