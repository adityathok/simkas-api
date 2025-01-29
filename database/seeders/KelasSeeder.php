<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitSekolah;
use App\Models\Pegawai;
use App\Models\TahunAjaran;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //tahun ajaran aktif
        $tahunAjaran = TahunAjaran::getActive();
        $tahunAjaran = $tahunAjaran?->id;

        //Ambil semua Unit
        $unitSekolahList = UnitSekolah::all();

        foreach ($unitSekolahList as $unitSekolah) {

            $tingkats = $unitSekolah->tingkat ? json_decode($unitSekolah->tingkat) : [1];
            $rombels = $unitSekolah->rombel ? json_decode($unitSekolah->rombel) : [];

            // Contoh sederhana mendapatkan id guru
            $pegawai = Pegawai::inRandomOrder()->first();

            foreach ($tingkats as $tingkat) {
                if ($rombels) {
                    foreach ($rombels as $rombel) {
                        Kelas::create([
                            'id'                => fake()->unique()->numerify('01XMPL###'),
                            'unit_sekolah_id'   => $unitSekolah->id,
                            'tingkat'           => $tingkat,
                            'tahun_ajaran'      => $tahunAjaran,
                            'nama'              => $tingkat . ' ' . $rombel,
                            'wali_id'           => $pegawai->user_id
                        ]);
                    }
                } else {
                    Kelas::create([
                        'id'                => fake()->unique()->numerify('01XMPL###'),
                        'unit_sekolah_id'   => $unitSekolah->id,
                        'tingkat'           => $tingkat,
                        'tahun_ajaran'      => $tahunAjaran,
                        'nama'              => $tingkat,
                        'wali_id'           => $pegawai->user_id
                    ]);
                }
            }
        }
    }
}
