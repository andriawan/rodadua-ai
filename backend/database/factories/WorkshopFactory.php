<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workshop>
 */
class WorkshopFactory extends Factory
{
    private static array $cities = [
        'Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang',
        'Medan', 'Makassar', 'Denpasar', 'Palembang', 'Bekasi',
    ];

    private static array $provinces = [
        'DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah',
        'Sumatera Utara', 'Sulawesi Selatan', 'Bali', 'Sumatera Selatan', 'Jawa Barat',
    ];

    public function definition(): array
    {
        $city = $this->faker->randomElement(self::$cities);
        $province = self::$provinces[array_search($city, self::$cities)];

        return [
            'name' => $this->faker->randomElement([
                'Bengkel '.$this->faker->lastName(),
                $this->faker->company().' Motor',
                'Auto '.$this->faker->lastName(),
                $this->faker->lastName().' Racing Service',
                'Mitra '.$this->faker->lastName(),
            ]),
            'description' => $this->faker->optional()->paragraph(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->companyEmail(),
            'website' => $this->faker->optional()->url(),
            'address' => $this->faker->streetAddress(),
            'city' => $city,
            'province' => $province,
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(-8, 6),
            'longitude' => $this->faker->longitude(95, 141),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'total_reviews' => $this->faker->numberBetween(0, 500),
            'specialist_motorcycle_count' => $this->faker->numberBetween(1, 20),
            'operating_hours' => $this->faker->randomElement([
                '08:00-17:00',
                '08:00-20:00',
                '07:00-21:00',
                '24 Hours',
                '09:00-18:00',
            ]),
            'is_open_weekends' => $this->faker->boolean(70),
            'services_offered' => $this->faker->randomElements([
                'Oil Change', 'Tire Service', 'Brake Service', 'Engine Tune-up',
                'Chain & Sprocket', 'Electrical System', 'Suspension', 'Body Repair',
                'Paint', 'Custom Build', 'Diagnostic', 'AC Service',
            ], $this->faker->numberBetween(2, 6)),
        ];
    }

    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 4.0, 5.0),
            'total_reviews' => $this->faker->numberBetween(50, 500),
        ]);
    }
}
