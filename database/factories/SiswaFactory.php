<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender     = fake()->randomElement(['male', 'female']);
        $first_name = fake()->firstName($gender);
        $last_name  = fake()->lastName($gender);
        $jk         = ($gender === 'male') ? 'Laki-laki' : 'Perempuan';

        $longName = fake()->randomElement([2, 3, 4, 5]);
        if ($longName == 1) {
            $name = $first_name;
        } elseif ($longName == 2) {
            $name = $first_name . ' ' . $last_name . ' ' . fake()->lastName($gender);
        } elseif ($longName == 3) {
            $name = $first_name . ' ' . fake()->firstName($gender) . ' ' . $last_name . ' ' . fake()->lastName($gender);
        } else {
            $name = $first_name . ' ' . $last_name;
        }

        return [
            'nis'               => fake()->unique()->numerify('200#######'),
            'nisn'              => fake()->unique()->numerify('100#######'),
            'nama'              => $name,
            'nama_panggilan'    => Str::lower($first_name),
            'status'            => fake()->randomElement(['aktif', 'keluar', 'pindah', 'lulus']),
            'tempat_lahir'      => fake()->city(),
            'tanggal_lahir'     => fake()->date('Y-m-d', '2010-01-01'),
            'tanggal_masuk'     => fake()->date('Y-m-d', '2023-01-01'),
            'jenis_kelamin'     => $jk,
            'email'             => Str::lower($first_name) . Str::lower($last_name) . rand(1, 999) . '@' . fake()->safeEmailDomain(),
        ];
    }
}
