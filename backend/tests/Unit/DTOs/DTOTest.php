<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ComparisonDTO;
use App\DTOs\CreateMotorcycleDTO;
use App\DTOs\CreateUserDTO;
use App\DTOs\LoginDTO;
use App\DTOs\MaintenanceDTO;
use App\DTOs\TroubleshootDTO;
use App\DTOs\UpdateMotorcycleDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class DTOTest extends TestCase
{
    public function test_create_user_dto_from_request(): void
    {
        $request = new Request([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $dto = CreateUserDTO::fromRequest($request);

        $this->assertSame('Budi', $dto->name);
        $this->assertSame('budi@example.com', $dto->email);
        $this->assertSame('secret123', $dto->password);
    }

    public function test_login_dto_from_request(): void
    {
        $request = new Request([
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $dto = LoginDTO::fromRequest($request);

        $this->assertSame('budi@example.com', $dto->email);
        $this->assertSame('secret123', $dto->password);
    }

    public function test_create_motorcycle_dto_from_request(): void
    {
        $request = new Request([
            'brand' => 'Honda',
            'model' => 'Vario 160',
            'year' => '2024',
            'color' => 'Hitam',
            'engine_cc' => '160',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'odometer_km' => '5000',
        ]);

        $dto = CreateMotorcycleDTO::fromRequest($request);

        $this->assertSame('Honda', $dto->brand);
        $this->assertSame('Vario 160', $dto->model);
        $this->assertSame(2024, $dto->year);
        $this->assertSame(160, $dto->engine_cc);
        $this->assertSame(5000, $dto->odometer_km);

        $array = $dto->toArray();
        $this->assertSame('Honda', $array['brand']);
        $this->assertSame(2024, $array['year']);
        $this->assertSame(5000, $array['odometer_km']);
    }

    public function test_create_motorcycle_dto_defaults_odometer_to_zero(): void
    {
        $request = new Request([
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => '2023',
        ]);

        $dto = CreateMotorcycleDTO::fromRequest($request);

        $this->assertSame(0, $dto->odometer_km);
    }

    public function test_update_motorcycle_dto_filters_nulls(): void
    {
        $request = new Request([
            'brand' => 'Honda',
            'color' => 'Merah',
        ]);

        $dto = UpdateMotorcycleDTO::fromRequest($request);

        $array = $dto->toArray();
        $this->assertSame('Honda', $array['brand']);
        $this->assertSame('Merah', $array['color']);
        $this->assertArrayNotHasKey('model', $array);
        $this->assertArrayNotHasKey('year', $array);
    }

    public function test_troubleshoot_dto_from_request(): void
    {
        $request = new Request([
            'motorcycle_id' => '1',
            'problem_description' => 'Motor tidak bisa distarter',
            'symptoms' => 'Suara klik saat starter ditekan',
        ]);

        $dto = TroubleshootDTO::fromRequest($request);

        $this->assertSame(1, $dto->motorcycleId);
        $this->assertSame('Motor tidak bisa distarter', $dto->problemDescription);
        $this->assertSame('Suara klik saat starter ditekan', $dto->symptoms);

        $array = $dto->toArray();
        $this->assertSame(1, $array['motorcycle_id']);
    }

    public function test_troubleshoot_dto_without_symptoms(): void
    {
        $request = new Request([
            'motorcycle_id' => '2',
            'problem_description' => 'Mesin overheat',
        ]);

        $dto = TroubleshootDTO::fromRequest($request);

        $this->assertSame(2, $dto->motorcycleId);
        $this->assertNull($dto->symptoms);
    }

    public function test_maintenance_dto_from_request(): void
    {
        $request = new Request([
            'motorcycle_id' => '3',
            'history' => [
                ['date' => '2024-01-01', 'type' => 'Ganti Oli'],
            ],
        ]);

        $dto = MaintenanceDTO::fromRequest($request);

        $this->assertSame(3, $dto->motorcycleId);
        $this->assertCount(1, $dto->history);

        $array = $dto->toArray();
        $this->assertSame(3, $array['motorcycle_id']);
    }

    public function test_maintenance_dto_without_history(): void
    {
        $request = new Request([
            'motorcycle_id' => '4',
        ]);

        $dto = MaintenanceDTO::fromRequest($request);

        $this->assertSame(4, $dto->motorcycleId);
        $this->assertNull($dto->history);
    }

    public function test_comparison_dto_from_route_params(): void
    {
        $request = new Request([], [], [], [], [], [
            'REQUEST_URI' => '/api/v1/motorcycles/5/compare/10',
        ]);
        $request->setRouteResolver(function () use ($request) {
            return (new \Illuminate\Routing\Route(['POST'], '/api/v1/motorcycles/{motorcycle}/compare/{comparedMotorcycle}', []))
                ->bind($request);
        });
        $request->route()->setParameter('motorcycle', '5');
        $request->route()->setParameter('comparedMotorcycle', '10');

        $dto = ComparisonDTO::fromRequest($request);

        $this->assertSame(5, $dto->motorcycleId);
        $this->assertSame(10, $dto->comparedMotorcycleId);

        $array = $dto->toArray();
        $this->assertSame(5, $array['motorcycle_id']);
        $this->assertSame(10, $array['compared_motorcycle_id']);
    }
}
