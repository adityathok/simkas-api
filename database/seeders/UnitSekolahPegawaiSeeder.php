<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitSekolahPegawai;
use App\Models\UnitSekolah;
use App\Models\Pegawai;


class UnitSekolahPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pegawai dan unit sekolah
        $pegawaiList = Pegawai::all();
        $unitSekolahList = UnitSekolah::all();

        $pegawaiCount = $pegawaiList->count();
        $unitSekolahCount = $unitSekolahList->count();

        for ($i = 0; $i < $pegawaiCount; $i++) {
            $pegawai = $pegawaiList[$i];
            $unitSekolah = $unitSekolahList[$i % $unitSekolahCount];

            // Tentukan jabatan berdasarkan indeks 
            $jabatan = $i < 4 ? 'Kepala Sekolah' : 'Guru';

            UnitSekolahPegawai::create([
                'unit_sekolah_id' => $unitSekolah->id,
                'user_id' => $pegawai->user_id,
                'jabatan' => $jabatan
            ]);
        }
    }
}
