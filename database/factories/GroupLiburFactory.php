<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupLibur>
 */
class GroupLiburFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_waktu_libur' => \App\Models\WaktuLibur::factory(),
            'id_group' => \App\Models\Group::factory(),
        ];
    }
}
