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
        $tahunAjaran = $tahunAjaran?->nama;

        //semua tahun ajaran
        $tahunAjaranList = TahunAjaran::all();

        //Ambil semua Unit
        $unitSekolahList = UnitSekolah::all();

        foreach ($unitSekolahList as $unitSekolah) {

            $tingkats = $unitSekolah->tingkat ? $unitSekolah->tingkat : [1];
            $rombels = $unitSekolah->rombel ? $unitSekolah->rombel : [];

            foreach ($tingkats as $tingkat) {
                if ($rombels) {
                    foreach ($rombels as $rombel) {

                        //loop tahun ajaran
                        foreach ($tahunAjaranList as $tahunAjaranItem) {
                            // Contoh sederhana mendapatkan id guru
                            $pegawai = Pegawai::inRandomOrder()->first();

                            Kelas::create([
                                'unit_sekolah_id'   => $unitSekolah->id,
                                'tingkat'           => $tingkat,
                                'tahun_ajaran'      => $tahunAjaranItem->nama,
                                'nama'              => $tingkat . ' ' . $rombel,
                                'wali_id'           => $pegawai->user_id
                            ]);
                        }
                    }
                } else {
                    //loop tahun ajaran
                    foreach ($tahunAjaranList as $tahunAjaranItem) {

                        // Contoh sederhana mendapatkan id guru
                        $pegawai = Pegawai::inRandomOrder()->first();
                        Kelas::create([
                            'unit_sekolah_id'   => $unitSekolah->id,
                            'tingkat'           => $tingkat,
                            'tahun_ajaran'      => $tahunAjaranItem->nama,
                            'nama'              => $tingkat,
                            'wali_id'           => $pegawai->user_id
                        ]);
                    }
                }
            }
        }
    }
}
