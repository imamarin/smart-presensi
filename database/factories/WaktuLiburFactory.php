<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WaktuLibur>
 */
class WaktuLiburFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'nama_libur' => 'Libur ' . $this->faker->word(),
            'tanggal_mulai' => $mulai,
            'tanggal_akhir' => (clone $mulai)->modify('+2 days'),
        ];
    }
}
