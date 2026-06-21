<?php

namespace Database\Factories;

use App\Models\Maintenance;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
{
    private static array $types = [
        'Oil Change',
        'Tire Replacement',
        'Brake Service',
        'Chain Adjustment',
        'Spark Plug Replacement',
        'Coolant Flush',
        'Air Filter Replacement',
        'Battery Replacement',
        'Full Service',
        'Transmission Service',
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(self::$types);
        $cost = match ($type) {
            'Oil Change' => $this->faker->randomElement([50000, 75000, 100000, 150000]),
            'Tire Replacement' => $this->faker->randomElement([200000, 350000, 500000, 750000]),
            'Brake Service' => $this->faker->randomElement([75000, 100000, 150000, 200000]),
            'Chain Adjustment' => $this->faker->randomElement([25000, 50000, 75000]),
            'Spark Plug Replacement' => $this->faker->randomElement([50000, 75000, 100000]),
            'Coolant Flush' => $this->faker->randomElement([75000, 100000, 150000]),
            'Air Filter Replacement' => $this->faker->randomElement([35000, 50000, 75000]),
            'Battery Replacement' => $this->faker->randomElement([100000, 150000, 250000]),
            'Full Service' => $this->faker->randomElement([200000, 300000, 500000]),
            'Transmission Service' => $this->faker->randomElement([150000, 200000, 300000]),
        };

        return [
            'motorcycle_id' => Motorcycle::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'description' => $this->faker->sentence(),
            'odometer_km' => $this->faker->numberBetween(1000, 80000),
            'maintenance_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'cost' => $cost,
            'workshop' => $this->faker->company(),
            'status' => $this->faker->randomElement(['completed', 'scheduled', 'pending']),
            'next_maintenance_km' => $this->faker->optional()->numberBetween(10000, 40000),
            'next_maintenance_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
        ]);
    }
}
