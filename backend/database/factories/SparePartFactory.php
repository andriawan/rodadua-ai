<?php

namespace Database\Factories;

use App\Models\SparePart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SparePart>
 */
class SparePartFactory extends Factory
{
    private static array $categories = [
        'Engine', 'Brakes', 'Suspension', 'Electrical', 'Body', 'Exhaust',
        'Transmission', 'Cooling', 'Fuel System', 'Tires & Wheels',
    ];

    public function definition(): array
    {
        $category = $this->faker->randomElement(self::$categories);

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'part_number' => strtoupper($this->faker->bothify('???-####-##')),
            'oem_number' => strtoupper($this->faker->bothify('OEM-####-???')),
            'category' => $category,
            'subcategory' => $this->faker->optional()->word(),
            'brand' => $this->faker->randomElement(['Honda Genuine', 'Yamaha Genuine', 'AHM', 'Brembo', 'NGK', 'Denso', 'Mikuni', 'KYB', 'Showa', 'OHLINS']),
            'compatible_motorcycles' => $this->faker->randomElements([
                'Honda Vario 125', 'Honda Vario 160', 'Yamaha NMAX 155', 'Yamaha Aerox 155',
                'Honda Beat', 'Honda PCX 160', 'Yamaha MX King 150', 'Suzuki GSX R150',
            ], $this->faker->numberBetween(1, 4)),
            'compatible_years' => $this->faker->randomElements([2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026], $this->faker->numberBetween(2, 5)),
            'retail_price' => $this->faker->numberBetween(10000, 5000000),
            'wholesale_price' => $this->faker->numberBetween(8000, 4500000),
            'quantity_available' => $this->faker->numberBetween(0, 100),
            'in_stock' => $this->faker->boolean(80),
            'supplier_name' => $this->faker->company(),
            'supplier_code' => strtoupper($this->faker->bothify('SUP-####')),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'in_stock' => true,
            'quantity_available' => $this->faker->numberBetween(5, 100),
        ]);
    }
}
