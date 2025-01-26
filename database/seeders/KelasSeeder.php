<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitSekolah;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Ambil semua Unit
        $unitSekolahList = UnitSekolah::all();

        foreach ($unitSekolahList as $unitSekolah) {

            $tingkats = $unitSekolah->tingkat ? json_decode($unitSekolah->tingkat) : [1];
            $rombels = $unitSekolah->rombel ? json_decode($unitSekolah->rombel) : [];

            foreach ($tingkats as $tingkat) {
                if ($rombels) {
                    foreach ($rombels as $rombel) {
                        Kelas::create([
                            'unit_sekolah_id'   => $unitSekolah->id,
                            'tingkat'           => $tingkat,
                            'tahun_ajaran'      => date('Y', strtotime('-1 year')) . '/' . date('Y'),
                            'nama'              => $tingkat . ' ' . $rombel
                        ]);
                    }
                } else {
                    Kelas::create([
                        'unit_sekolah_id'   => $unitSekolah->id,
                        'tingkat'           => $tingkat,
                        'tahun_ajaran'      => date('Y', strtotime('-1 year')) . '/' . date('Y'),
                        'nama'              => $tingkat
                    ]);
                }
            }
        }
    }
}
