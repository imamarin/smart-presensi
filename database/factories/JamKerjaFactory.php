<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JamKerja>
 */
class JamKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '17:00:00',
            'toleransi_check_out' => 8,
            'toleransi_terlambat' => 10,
            'toleransi_pulang_cepat' => 10,
            'jam_mulai_scan_masuk' => '07:30:00',
            'jam_mulai_scan_keluar' => '16:30:00',
            // 'status_check_in' => 'on-time',
            // 'status_check_out' => 'on-time',
        ];
    }
}
