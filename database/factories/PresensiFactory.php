<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Presensi>
 */
class PresensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $masuk = $this->faker->dateTimeBetween('-1 week', 'now');
        return [
            'id_participant' => \App\Models\Participant::factory(),
            'waktu_masuk' => $masuk,
            'waktu_keluar' => (clone $masuk)->modify('+9 hours'),
            'status_terlambat' => $this->faker->boolean(0.5),
            'status_check_out' => $this->faker->boolean(),
            'id_device' => \App\Models\Device::factory(),
            'id_shift' => \App\Models\Shift::factory(),
        ];
    }
}
