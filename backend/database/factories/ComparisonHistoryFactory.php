<?php

namespace Database\Factories;

use App\Models\ComparisonHistory;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComparisonHistory>
 */
class ComparisonHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'motorcycle_id' => Motorcycle::factory(),
            'compared_motorcycle_id' => Motorcycle::factory(),
            'comparison_data' => [
                'differences' => [
                    'engine_cc' => ['left' => 150, 'right' => 160],
                    'price_range' => ['left' => '20-25jt', 'right' => '25-30jt'],
                    'fuel_consumption' => ['left' => '45 km/L', 'right' => '42 km/L'],
                ],
                'similarities' => [
                    'transmission' => 'Automatic',
                    'fuel_type' => 'Pertalite',
                    'type' => 'Scooter',
                ],
                'verdict' => $this->faker->sentence(),
            ],
            'summary' => $this->faker->paragraph(),
        ];
    }
}
