<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = Carbon::now()->month;

        // Jika bulan masih Januari hingga Juni, maka tahun ajaran aktif adalah yang dimulai pada tahun sebelumnya
        if ($bulanSekarang <= 6) {
            $tahunAktifMulai = $tahunSekarang - 1;
        } else {
            $tahunAktifMulai = $tahunSekarang;
        }

        for ($i = -5; $i <= 5; $i++) {
            $tahunMulai = $tahunSekarang + $i;
            $tglMulai = $tahunMulai . '-07-01';
            $tahunAkhir = $tahunMulai + 1;
            $tglAkhir = $tahunAkhir . '-06-30';
            $active = false;

            //jika tgl mulai tahun sekarang
            if ($tahunMulai == $tahunAktifMulai) {
                $active = true;
            }

            TahunAjaran::updateOrCreate(
                ['id' => $tahunMulai . '_' . $tahunAkhir],
                [
                    'nama'      => $tahunMulai . '/' . $tahunAkhir,
                    'mulai'     => $tglMulai,
                    'akhir'     => $tglAkhir,
                    'active'    => $active
                ]
            );
        }
    }
}
