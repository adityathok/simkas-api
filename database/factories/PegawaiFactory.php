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
            'status'        => fake()->randomElement(['Aktif', 'Keluar']),
            'tempat_lahir'  => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
            'tanggal_masuk' => fake()->date('Y-m-d', '2023-01-01'),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'email'         => fake()->unique()->safeEmail(),
        ];
    }
}
