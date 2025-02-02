<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitSekolah>
 */
class UnitSekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city       = fake()->city();
        $jenjang    = fake()->randomElement(['TK', 'KB', 'SD', 'SMP', 'SMA']);
        return [
            'id'        => fake()->unique()->numerify('01XMPL#########'),
            'nama'      => $jenjang . ' ' . $city . ' ' . fake()->numberBetween(1, 99),
            'jenjang'   => $jenjang,
            'alamat'    => fake()->address(),
            'desa'      => fake()->streetName(),
            'kecamatan' => $city,
            'kota'      => $city,
            'provinsi'  => fake()->state(),
            'kode_pos'  => fake()->postcode(),
            'status'    => 'aktif',
            'whatsapp'  => fake()->unique()->numerify('08#############'),
            'telepon'   => fake()->unique()->numerify('02#############'),
            'email'     => fake()->unique()->safeEmail(),
            'tanggal_berdiri' => fake()->date('Y-m-d', '2000-01-01'),
            'tingkat'  => [1, 2, 3],
            'rombel'   => ['A', 'B', 'C'],
        ];
    }
}
