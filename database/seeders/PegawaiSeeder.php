<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Pegawai;
use App\Models\UserAlamat;
use App\Models\UserMeta;

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
                'nip'           => fake()->unique()->numerify('197###########'), // Example NIP format
                'nama'          => fake()->name(),
                'status'        => fake()->randomElement(['Aktif', 'Keluar']),
                'tempat_lahir'  => fake()->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
                'tanggal_masuk' => fake()->date('Y-m-d', '2023-01-01'),
                'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
                'email'         => fake()->unique()->safeEmail(),
            ]);

            $user_id = $pegawai->user_id;

            //Alamat User
            UserAlamat::create([
                'user_id'       => $user_id,
                'alamat'        => fake()->address(),
                'rt'            => fake()->randomNumber(2),
                'rw'            => fake()->randomNumber(2),
                'dusun'         => fake()->streetName(),
                'kelurahan'     => fake()->city(),
                'kecamatan'     => fake()->city(),
                'kota'          => fake()->city(),
                'provinsi'      => fake()->state(),
                'kode_pos'      => fake()->postcode(),
                'jenis_tinggal' => fake()->randomElement(['Rumah', 'Kontrakan', 'Kost']),
                'transportasi'  => fake()->randomElement(['Kendaraan Pribadi', 'Kendaraan Umum', 'Jalan Kaki']),
                'jarak'         => fake()->randomNumber(3),
            ]);

            //Meta user
            $metaKeys = ['golongan', 'nik', 'pendidikan_terakhir', 'no_rekening'];
            foreach ($metaKeys as $key) {
                UserMeta::create([
                    'user_id'    => $user_id,
                    'meta_key'           => $key,
                    'meta_value'         => match ($key) {
                        'golongan'              => fake()->randomElement(['III/a', 'III/b', 'IV/a']),
                        'nik'                   => fake()->unique()->numerify('330###########'),
                        'pendidikan_terakhir'   => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
                        'no_rekening'           => fake()->bankAccountNumber(),
                        default => null,
                    },
                ]);
            }
        }
    }
}
