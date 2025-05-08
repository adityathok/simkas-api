<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\UnitSekolah;
use App\Models\Pegawai;

class UnitSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //get setting jenjang
        $jenjangs = Setting::get('jenjang');

        // Jika tidak ada pegawai, hentikan proses
        if (empty($jenjangs)) {
            $this->command->warn('Tidak ada data jenjang. Seeder UnitSekolah dihentikan.');
            return;
        }

        foreach ($jenjangs as $jenjang) {

            //ambil jenjang
            switch ($jenjang) {
                case 'TK':
                    $tingkat = ['A', 'B'];
                    $rombel = ['Kecil', 'Besar'];
                    break;
                case 'KB':
                    $tingkat = ['A', 'B'];
                    $rombel = ['Kecil', 'Besar'];
                    break;
                case 'SD':
                    $tingkat = [1, 2, 3, 4, 5, 6];
                    $rombel = ['A', 'B', 'C'];
                    break;
                case 'SMP':
                    $tingkat = [7, 8, 9];
                    $rombel = ['A', 'B', 'C'];
                    break;
                case 'SMA':
                    $tingkat = [10, 11, 12];
                    $rombel = ['A', 'B', 'C', 'D', 'E', 'F'];
                    break;
                default:
                    $tingkat = [1, 2, 3];
                    $rombel = ['A'];
                    break;
            }

            //buat unit
            $city = fake()->city();
            $unitsekolah = UnitSekolah::create([
                'nama'      => $jenjang . ' ' . $city . ' ' . fake()->numberBetween(1, 99),
                'jenjang'   => $jenjang,
                'alamat'    => fake()->address(),
                'desa'      => fake()->streetName(),
                'kecamatan' => $city,
                'kota'      => $city,
                'provinsi'  => fake()->state(),
                'kode_pos'  => fake()->postcode(),
                'status'    => 'aktif',
                'whatsapp'  => fake()->phoneNumber(),
                'telepon'   => fake()->phoneNumber(),
                'email'     => fake()->unique()->safeEmail(),
                'tanggal_berdiri' => fake()->date('Y-m-d', '2000-01-01'),
                'tingkat'  => $tingkat,
                'rombel'   => $rombel,
            ]);
        }
    }
}
