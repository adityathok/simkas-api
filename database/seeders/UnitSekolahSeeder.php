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
            //buat unit
            $city = fake()->city();
            $unitsekolah = UnitSekolah::create([
                'id'        => fake()->regexify('[A-Z]{3}[0-4]{5}'),
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
            ]);
        }
    }
}
