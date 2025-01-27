<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkat = fake()->randomBetween(1, 9);
        return [
            'id'            => fake()->unique()->numerify('01XMPL#########'),
            'nama'          => $tingkat . ' ' . fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'tingkat'       => $tingkat,
            'tahun_ajaran'  => fake()->year('-1 year') . '_' . fake()->year(),
        ];
    }
}
