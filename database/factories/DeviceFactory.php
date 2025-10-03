<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'Device ' . $this->faker->word(),
            'device_id' => strtoupper($this->faker->bothify('SN###')),
            'lokasi' => $this->faker->address(),
            'api_key' => Str::random(32),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'status_koneksi' => now()
        ];
    }
}
