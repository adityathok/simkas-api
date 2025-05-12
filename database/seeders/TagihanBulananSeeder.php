<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\TagihanMaster;
use App\Models\Tagihan;
use App\Models\Siswa;
use Carbon\Carbon;

class TagihanBulananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //dapatkan data siswa yang memiliki status aktif
        $siswas = Siswa::where('status', 'aktif')->get();

        //jika tidak ada data siswa
        if ($siswas->isEmpty()) {
            $this->command->warn('Tidak ada data siswa yang aktif.');
            return;
        }

        //total siswa
        $totalSiswa = $siswas->count();
        $this->command->info('Membuat tagihan untuk ' . $totalSiswa . ' siswa.');

        //random tabungan
        $min = 500000;
        $max = 1000000;
        $step = 500000;

        // Generate a random multiple of 50,000
        $randomNominal = $min + ($step * rand(0, ($max - $min) / $step));

        //random pendapatan_id
        $pendapatan = ['INSPPEN', 'INSARPRAS'];
        $pendapatan_id = $pendapatan[array_rand($pendapatan)];

        //periode
        $now = Carbon::now();
        $periode_start = '06-' . $now->format('Y');
        $nextyear = Carbon::now()->addYear(1);
        $periode_end = '05-' . $nextyear->format('Y');

        //buat TagihanMaster
        $name_tagihan = $pendapatan_id == 'INSPPEN' ? 'SPP Pendidikan' : 'Pembangunan Sarpras';
        $master = TagihanMaster::create([
            'nama'                 => $name_tagihan,
            'nominal'              => $randomNominal,
            'type'                 => 'bulanan',
            'total_tagihan'        => $totalSiswa,
            'total_nominal'        => ($totalSiswa * $randomNominal),
            'keterangan'           => $name_tagihan,
            'periode_start'        => $periode_start,
            'periode_end'          => $periode_end,
            'due_date'             => null,
            'akun_pendapatan_id' => $pendapatan_id,
            'tahun_ajaran' => null,
            'unit_sekolah_id' => null,
            'kelas_id' => null,
            'user_type' => 'siswa',
            'admin_id' => null,
        ]);

        $this->command->info('Tagihan Master berhasil dibuat');

        //buat tagihan selama periode bulanan
        $start = Carbon::createFromFormat('m-Y', $periode_start); // Tanggal mulai
        $end = Carbon::createFromFormat('m-Y', $periode_end);   // Tanggal selesai

        // loop daftar bulan
        while ($start <= $end) {
            $month = $start->format('m-Y'); // Format bulan

            // Persiapkan tagihan untuk semua siswa
            $tagihans = $siswas->map(function ($siswa) use ($master, $start, $name_tagihan) {

                $tgl = $start->format('Y-m');

                return [
                    'nomor'             => 'INV' . Str::ulid(),
                    'user_id'           => $siswa->user_id,
                    'tanggal'           => $tgl . '-01 00:01:00',
                    'tagihan_master_id' => $master->id,
                    'status'            => 'belum',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            });

            // Masukkan data ke tabel tagihans
            Tagihan::insert($tagihans->toArray());

            $this->command->info('Tagihan ' . $month . ' berhasil dibuat');

            $start->addMonth(); // Tambah satu bulan
        }
    }
}
