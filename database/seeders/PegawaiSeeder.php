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
        //buat 20 data pegawai
        $pegawais = Pegawai::factory()->count(20)->create();

        foreach ($pegawais as $pegawai) {
            $user_id    = $pegawai->user_id;
            $city       = fake()->city();

            //buat alamat user
            UserAlamat::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'alamat'        => fake()->address(),
                    'rt'            => fake()->numberBetween(1, 20),
                    'rw'            => fake()->randomNumber(1, 10),
                    'dusun'         => fake()->streetName(),
                    'kelurahan'     => $city,
                    'kecamatan'     => $city,
                    'kota'          => $city,
                    'provinsi'      => fake()->state(),
                    'kode_pos'      => fake()->postcode(),
                    'jenis_tinggal' => fake()->randomElement(['Rumah', 'Kontrakan', 'Kost']),
                    'transportasi'  => fake()->randomElement(['Kendaraan Pribadi', 'Kendaraan Umum', 'Jalan Kaki']),
                    'jarak'         => fake()->randomNumber(3),
                ]
            );

            //Meta user
            $metaKeys = ['golongan', 'nik', 'pendidikan_terakhir', 'no_rekening'];
            foreach ($metaKeys as $key) {
                UserMeta::create([
                    'user_id'       => $user_id,
                    'meta_key'      => $key,
                    'meta_value'    => match ($key) {
                        'golongan'              => fake()->randomElement(['III/a', 'III/b', 'IV/a']),
                        'nik'                   => fake()->unique()->numerify('330###########'),
                        'pendidikan_terakhir'   => fake()->randomElement(['SMA', 'D3', 'S1', 'S2']),
                        'no_rekening'           => fake()->bankAccountNumber(),
                        default                 => null,
                    },
                ]);
            }
        }
    }
}
