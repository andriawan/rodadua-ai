<?php

namespace Database\Factories;

use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Motorcycle>
 */
class MotorcycleFactory extends Factory
{
    private static array $indonesianBrands = [
        'Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'TVS',
    ];

    private static array $models = [
        'Honda' => ['Vario 125', 'Vario 160', 'PCX 160', 'Beat', 'Scoopy', 'CBR150R', 'CBR250RR', 'Rebel 500', 'CB150X', 'ADV 160'],
        'Yamaha' => ['NMAX 155', 'Aerox 155', 'MX King 150', 'R15', 'R25', 'XSR 155', 'WR155R', 'Gear 125', 'Fazzio', 'Grand Filano'],
        'Suzuki' => ['GSX R150', 'GSX S150', 'Address 125', 'Nex II', 'Hayabusa', 'V-Strom 250'],
        'Kawasaki' => ['Ninja 250', 'Ninja ZX-25R', 'W175', 'KLX 150', 'Versys X 250', 'Z250'],
        'TVS' => ['Apache RTR 160', 'Apache RTR 200', 'Jupiter 125', 'XL100'],
    ];

    public function definition(): array
    {
        $brand = $this->faker->randomElement(self::$indonesianBrands);
        $model = $this->faker->randomElement(self::$models[$brand]);

        return [
            'user_id' => User::factory(),
            'brand' => $brand,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2026),
            'color' => $this->faker->randomElement(['Hitam', 'Putih', 'Merah', 'Biru', 'Silver', 'Hijau', 'Abu-abu', 'Kuning']),
            'license_plate' => strtoupper($this->faker->bothify('? #### ??')),
            'engine_cc' => $this->faker->randomElement([110, 125, 150, 155, 160, 200, 250, 500, 650, 1000]),
            'engine_type' => $this->faker->randomElement(['4-Stroke', '4-Stroke DOHC', '4-Stroke SOHC']),
            'transmission' => $this->faker->randomElement(['Automatic', 'Manual', 'Semi-Automatic']),
            'fuel_type' => $this->faker->randomElement(['Pertalite', 'Pertamax', 'Pertamax Turbo', 'Solar']),
            'purchase_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'odometer_km' => $this->faker->numberBetween(0, 80000),
            'notes' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'for_sale']),
            'is_favorite' => $this->faker->boolean(20),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function favorite(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_favorite' => true,
        ]);
    }
}
