<?php

namespace Tests\Unit\Services;

use App\DTOs\TroubleshootDTO;
use App\Models\User;
use App\Services\AiLogService;
use App\Services\ComparisonService;
use App\Services\MaintenanceService;
use App\Services\SpecificationService;
use App\Services\TroubleshootingService;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    // === AiLogService ===

    public function test_ai_log_service_logs_success(): void
    {
        $service = app(AiLogService::class);

        $log = $service->log([
            'user_id' => $this->user->id,
            'provider' => 'openai',
            'model' => 'gpt-4o-mini',
            'prompt_key' => 'troubleshooting.analyze',
            'status' => 'success',
        ]);

        $this->assertNotNull($log->id);
        $this->assertSame('openai', $log->provider);
        $this->assertSame('success', $log->status);
    }

    public function test_ai_log_service_logs_error(): void
    {
        $service = app(AiLogService::class);

        $log = $service->logError(
            'openai',
            'API key invalid',
            $this->user->id,
            'troubleshooting.analyze'
        );

        $this->assertNotNull($log->id);
        $this->assertSame('error', $log->status);
        $this->assertSame('API key invalid', $log->error_message);
    }

    // === TroubleshootingService ===

    public function test_troubleshooting_service_analyze_problem_stores_result(): void
    {
        $service = app(TroubleshootingService::class);
        $motorcycle = $this->createMotorcycle($this->user);

        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('chat')
            ->once()
            ->andReturn('Diagnosa Awal: Kemungkinan aki lemah. **Tingkat Keparahan**: Rendah');
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        // Re-resolve service with mocked AiManager
        $service = app(TroubleshootingService::class);

        $dto = new TroubleshootDTO(
            motorcycleId: $motorcycle->id,
            problemDescription: 'Motor tidak bisa distarter',
            symptoms: 'Suara klik saat starter ditekan',
        );

        $result = $service->analyzeProblem($dto, $this->user->id);

        $this->assertNotNull($result->id);
        $this->assertSame('Motor tidak bisa distarter', $result->problem_description);
        $this->assertStringContainsString('Diagnosa Awal', $result->ai_analysis);
        $this->assertSame('low', $result->severity);
        $this->assertSame('open', $result->status);
    }

    public function test_troubleshooting_service_denies_access_to_other_user(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $otherUser = $this->createUser();
        $motorcycle = $this->createMotorcycle($otherUser);

        $service = app(TroubleshootingService::class);

        $dto = new TroubleshootDTO(
            motorcycleId: $motorcycle->id,
            problemDescription: 'Test',
            symptoms: null,
        );

        $service->analyzeProblem($dto, $this->user->id);
    }

    // === MaintenanceService ===

    public function test_maintenance_service_recommendations_returns_data(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);
        $expectedResponse = '**Perawatan Rutin**: Ganti oli setiap 3000 km';

        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('chat')
            ->once()
            ->andReturn($expectedResponse);
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        $service = app(MaintenanceService::class);
        $result = $service->getRecommendations($motorcycle->id, $this->user->id);

        $this->assertArrayHasKey('recommendations', $result);
        $this->assertSame($motorcycle->id, $result['motorcycle']['id']);
        $this->assertStringContainsString('Perawatan Rutin', $result['recommendations']);
    }

    public function test_maintenance_service_predict_returns_analysis(): void
    {
        $motorcycle = $this->createMotorcycle($this->user);

        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('chat')
            ->once()
            ->andReturn('Berdasarkan riwayat, perawatan selanjutnya pada 5000 km');
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        $service = app(MaintenanceService::class);
        $result = $service->predictNextMaintenance($motorcycle->id, $this->user->id);

        $this->assertArrayHasKey('prediction', $result);
        $this->assertSame(0, $result['history_count']);
    }

    // === ComparisonService ===

    public function test_comparison_service_compares_motorcycles(): void
    {
        $motorcycleA = $this->createMotorcycle($this->user);
        $motorcycleB = $this->createMotorcycle($this->user);

        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('chat')
            ->once()
            ->andReturn('**Perbedaan Utama**: Motor A lebih irit bahan bakar');
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        $service = app(ComparisonService::class);
        $result = $service->compare(
            $motorcycleA->id,
            $motorcycleB->id,
            $this->user->id
        );

        $this->assertNotNull($result->id);
        $this->assertSame($motorcycleA->id, $result->motorcycle_id);
        $this->assertSame($motorcycleB->id, $result->compared_motorcycle_id);
        $this->assertStringContainsString('Perbedaan Utama', $result->summary);
    }

    // === SpecificationService ===

    public function test_specification_service_extract_specs(): void
    {
        $expected = [
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'engine_cc' => 160,
            'engine_type' => '4-stroke',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'color' => 'Hitam',
        ];

        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('generateStructured')
            ->once()
            ->andReturn($expected);
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        $service = app(SpecificationService::class);
        $result = $service->extractSpecs('Honda Vario 160 tahun 2024 warna hitam');

        $this->assertSame('Honda', $result['brand']);
        $this->assertSame(2024, $result['year']);
    }

    public function test_specification_service_validate_specs(): void
    {
        /** @var \Mockery\MockInterface|\App\Services\Ai\AiManager $aiMock */
        $aiMock = \Mockery::mock(\App\Services\Ai\AiManager::class);
        $aiMock->shouldReceive('chat')
            ->once()
            ->andReturn('Spesifikasi terlihat konsisten untuk Honda Vario 160');
        app()->instance(\App\Services\Ai\AiManager::class, $aiMock);

        $service = app(SpecificationService::class);
        $result = $service->validateSpecs([
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => 2024,
            'engine_cc' => 160,
        ]);

        $this->assertArrayHasKey('validation', $result);
        $this->assertArrayHasKey('specs', $result);
    }
}
