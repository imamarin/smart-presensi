<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->randomElement(['Guru', 'Staff', 'Murid']),
            'tanggal_mulai' => $this->faker->date(),
            // 'hitungan_lembur' => $this->faker->randomFloat(2, 0, 5),
            'id_jam_kerja' => \App\Models\JamKerja::factory(),
        ];
    }
}
