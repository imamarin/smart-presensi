<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_induk' => $this->faker->unique()->numerify('ID###'),
            'nama' => $this->faker->name(),
            'id_kartu' => strtoupper($this->faker->bothify('CARD####')),
            'no_hp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}
