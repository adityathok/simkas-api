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

class TagihanSeeder extends Seeder
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
        $min = 50000;
        $max = 1000000;
        $step = 50000;

        // Generate a random multiple of 50,000
        $randomNominal = $min + ($step * rand(0, ($max - $min) / $step));

        //random pendapatan_id
        $pendapatan = ['INBUKU', 'INSERAGAM'];
        $pendapatan_id = $pendapatan[array_rand($pendapatan)];

        //tanggal, carbon 3 bulan kebelakang
        $startDate = Carbon::now()->subMonths(3); // 3 months ago
        $endDate = Carbon::now(); // Today
        $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
        $randomDate = Carbon::createFromTimestamp($randomTimestamp)->format('Y-m-d H:i:s');

        //buat TagihanMaster
        $name_tagihan = $pendapatan_id == 'INBUKU' ? 'Beli Buku' : 'Beli Seragam';
        $master = TagihanMaster::create([
            'nama' => $name_tagihan,
            'nominal' => $randomNominal,
            'type'  => 'insidental',
            'total_tagihan' => $totalSiswa,
            'total_nominal' => ($totalSiswa * $randomNominal),
            'keterangan' => $name_tagihan,
            'due_date' => null,
            'akun_pendapatan_id' => $pendapatan_id,
            'tahun_ajaran' => null,
            'unit_sekolah_id' => null,
            'kelas_id' => null,
            'user_type' => 'siswa',
            'admin_id' => null,
        ]);

        $this->command->info('Tagihan Master berhasil dibuat');

        // Persiapkan tagihan untuk semua siswa
        $tagihans = $siswas->map(function ($siswa) use ($master, $randomDate, $name_tagihan) {

            $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $randomDate);

            return [
                'nomor' => 'INV' . Str::ulid(),
                'tanggal' => $tanggal,
                'user_id' => $siswa->user_id,
                'tagihan_master_id' => $master->id,
                'status' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        // Masukkan data ke tabel tagihans
        Tagihan::insert($tagihans->toArray());

        $this->command->info('Tagihan untuk siswa berhasil dibuat');
    }
}
