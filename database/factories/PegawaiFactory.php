<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip'           => fake()->unique()->numerify('200XMPL#######'),
            'nama'          => fake()->name(),
            'status'        => fake()->randomElement(['aktif', 'keluar']),
            'tempat_lahir'  => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-30 years', '-20 years'),
            'tanggal_masuk' => fake()->dateTimeBetween('-10 years', '-1 years'),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'email'         => fake()->unique()->safeEmail(),
        ];
    }
}
