<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\PegawaiAlamat;
use App\Models\PegawaiMeta;
use Illuminate\Support\Str;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //buat 50 data pegawai
        for ($i = 1; $i <= 50; $i++) {

            // Create Pegawai
            $pegawai = Pegawai::create([
                'nip' => fake()->unique()->numerify('197###########'), // Example NIP format
                'nama' => fake()->name(),
                'status' => fake()->randomElement(['aktif', 'non-aktif']),
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
                'tanggal_masuk' => fake()->date('Y-m-d', '2023-01-01'),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'nik' => fake()->numerify('33##############'),
                'email' => fake()->unique()->safeEmail(),
                'user_id' => null,
            ]);

            // Create Alamat
            PegawaiAlamat::create([
                'pegawai_id' => $pegawai->id,
                'alamat' => fake()->address(),
                'rt' => fake()->numerify('00#'),
                'rw' => fake()->numerify('00#'),
                'dusun' => fake()->streetName(),
                'kelurahan' => fake()->citySuffix(),
                'kecamatan' => fake()->city(),
                'kota' => fake()->city(),
                'provinsi' => fake()->state(),
                'kode_pos' => fake()->postcode(),
                'jenis_tinggal' => fake()->randomElement(['Rumah', 'Kos', 'Kontrakan']),
                'transportasi' => fake()->randomElement(['Motor', 'Mobil', 'Sepeda', 'Jalan Kaki']),
                'jarak' => fake()->numberBetween(1, 30), // Jarak dalam km
            ]);

            // Create Meta
            $metaKeys = ['golongan', 'jabatan', 'pendidikan_terakhir', 'no_rekening'];
            foreach ($metaKeys as $key) {
                PegawaiMeta::create([
                    'pegawai_id' => $pegawai->id,
                    'key' => $key,
                    'value' => match ($key) {
                        'golongan' => fake()->randomElement(['III/a', 'III/b', 'IV/a']),
                        'jabatan' => fake()->jobTitle(),
                        'pendidikan_terakhir' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
                        'no_rekening' => fake()->bankAccountNumber(),
                        default => null,
                    },
                ]);
            }
        }
    }
}
