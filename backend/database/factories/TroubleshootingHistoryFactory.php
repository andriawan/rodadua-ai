<?php

namespace Database\Factories;

use App\Models\Motorcycle;
use App\Models\TroubleshootingHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TroubleshootingHistory>
 */
class TroubleshootingHistoryFactory extends Factory
{
    private static array $problems = [
        'Motor tidak bisa distarter',
        'Suara mesin kasar saat idle',
        'Tarikan berat saat akselerasi',
        'Lampu mati saat dikendarai',
        'Knalpot mengeluarkan asap putih',
        'Getaran berlebih pada kecepatan tinggi',
        'Transmisi sulit dipindahkan',
        'Borong bensin',
        'Rem bunyi saat digunakan',
        'Overheat setelah dipakai 30 menit',
    ];

    public function definition(): array
    {
        return [
            'motorcycle_id' => Motorcycle::factory(),
            'user_id' => User::factory(),
            'problem_description' => $this->faker->randomElement(self::$problems),
            'symptom' => $this->faker->paragraph(),
            'ai_analysis' => $this->faker->optional()->paragraphs(3, true),
            'suggested_solutions' => $this->faker->optional()->randomElements([
                'Cek busi dan ganti jika perlu',
                'Bersihkan karburator/injektor',
                'Cek kondisi oli mesin',
                'Periksa sistem kelistrikan',
                'Setel ulang valve clearance',
                'Ganti filter udara',
                'Cek sistem pendinginan',
                'Lakukan tune-up mesin',
                'Periksa kampas rem',
            ], $this->faker->numberBetween(1, 4)),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved']),
            'resolution_notes' => $this->faker->optional()->sentence(),
            'resolved_date' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'workshop_feedback' => $this->faker->optional()->sentence(),
            'ai_provider' => 'openai',
            'prompt_used' => $this->faker->optional()->word(),
            'user_rating' => $this->faker->optional()->numberBetween(1, 5),
            'user_feedback' => $this->faker->optional()->sentence(),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_date' => now(),
        ]);
    }
}
