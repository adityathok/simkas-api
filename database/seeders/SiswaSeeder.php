<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\UserAlamat;
use App\Models\UserMeta;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //dapatkan daftar kelas
        $getkelas = Kelas::all();

        foreach ($getkelas as $kelas) {
            //loop 2 - 5 siswa
            for ($i = 0; $i < rand(2, 5); $i++) {
                $siswa = Siswa::factory()->create();

                //update kelas siswa
                $siswa->kelas()->attach($kelas->id, ['active' => true]);

                //buat alamat user siswa
                $user_id    = $siswa->user_id;
                $city       = fake()->city();
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

                //buat meta user siswa
                $metaKeys = [
                    'nik',
                    'alat_transportasi',
                    'telepon',
                    'hp',
                    'whatsapp',
                    'skhun',
                    'penerima_kps',
                    'nomor_kps',
                    'ukur_baju',
                    'golongan_darah',
                    'alamat_orangtua',
                    'no_peserta_ujian',
                    'no_seri_ijazah',
                    'penerima',
                    'nomor_kip',
                    'nama_di_kip',
                    'nomor_kks',
                    'nomor_registrasi',
                    'bank',
                    'nomor_rekening',
                    'rekening_atas',
                    'layak_pip',
                    'kebutuhan_khusus',
                    'sekolah_asal',
                    'alamat_sekolah_asal',
                    'status_sekolah_asal',
                    'anak_ke',
                    'bahasa',
                    'agama',
                    'harapan',
                    'donasi_sumbangan_sarpras',
                    'donasi_sumbangan_sarpras_sub',
                    'donasi_sumbangan_gedung',
                    'donasi_sumbangan_gedung_sub',
                    'pembelian_uang_sragam',
                    'pembelian_uang_sragam_lainnya',
                    'pembiayaan_ekstrakurikuler',
                    'pembiayaan_mos',
                    'pembiayaan_spp',
                    'pembiayaan_spp_sub'
                ];
                foreach ($metaKeys as $key) {
                    UserMeta::create([
                        'user_id'       => $user_id,
                        'meta_key'      => $key,
                        'meta_value'    => match ($key) {
                            'nik'                           => fake()->unique()->numerify('330###########'),
                            'alat_transportasi'             => fake()->randomElement(['sepeda', 'motor', 'mobil', 'jalan kaki']),
                            'telepon'                       => fake()->phoneNumber(),
                            'hp'                            => fake()->phoneNumber(),
                            'whatsapp'                      => fake()->phoneNumber(),
                            'skhun'                         => fake()->numerify('########'),
                            'penerima_kps'                  => fake()->boolean() ? 'Ya' : 'Tidak',
                            'nomor_kps'                     => fake()->numerify('KPS#######'),
                            'ukur_baju'                     => fake()->randomElement(['S', 'M', 'L', 'XL']),
                            'golongan_darah'                => fake()->randomElement(['A', 'B', 'AB', 'O']),
                            'alamat_orangtua'               => fake()->address(),
                            'no_peserta_ujian'              => fake()->numerify('UJIAN########'),
                            'no_seri_ijazah'                => fake()->numerify('IJAZAH########'),
                            'penerima'                      => fake()->boolean() ? 'Ya' : 'Tidak',
                            'nomor_kip'                     => fake()->numerify('KIP#######'),
                            'nama_di_kip'                   => fake()->name(),
                            'nomor_kks'                     => fake()->numerify('KKS#######'),
                            'nomor_registrasi'              => fake()->numerify('REG########'),
                            'bank'                          => fake()->randomElement(['BCA', 'Mandiri', 'BRI', 'BNI']),
                            'nomor_rekening'                => fake()->bankAccountNumber(),
                            'rekening_atas'                 => fake()->name(),
                            'layak_pip'                     => fake()->boolean() ? 'Ya' : 'Tidak',
                            'kebutuhan_khusus'              => fake()->randomElement(['Tidak', 'Netra', 'Rungu', 'Grahita']),
                            'sekolah_asal'                  => fake()->company(),
                            'alamat_sekolah_asal'           => fake()->address(),
                            'status_sekolah_asal'           => fake()->randomElement(['Negeri', 'Swasta']),
                            'anak_ke'                       => fake()->numberBetween(1, 10),
                            'bahasa'                        => fake()->languageCode(),
                            'agama'                         => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']),
                            'harapan'                       => fake()->sentence(),
                            'donasi_sumbangan_sarpras'      => fake()->randomFloat(2, 1000, 10000),
                            'donasi_sumbangan_sarpras_sub'  => fake()->sentence(),
                            'donasi_sumbangan_gedung'       => fake()->randomFloat(2, 1000, 10000),
                            'donasi_sumbangan_gedung_sub'   => fake()->sentence(),
                            'pembelian_uang_sragam'         => fake()->randomFloat(2, 1000, 10000),
                            'pembelian_uang_sragam_lainnya' => fake()->randomFloat(2, 1000, 10000),
                            'pembiayaan_ekstrakurikuler'    => fake()->randomFloat(2, 1000, 10000),
                            'pembiayaan_mos'                => fake()->randomFloat(2, 1000, 10000),
                            'pembiayaan_spp'                => fake()->randomFloat(2, 1000, 10000),
                            'pembiayaan_spp_sub'            => fake()->randomFloat(2, 1000, 10000),
                            default                         => null,
                        },
                    ]);
                }
            }
        }
    }
}
