<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Transaksi;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
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
        $this->command->info('Membuat transaksi untuk ' . $totalSiswa . ' siswa.');

        $siswas->each(function ($siswa) {

            //random tabungan
            $min = 50000;
            $max = 1000000;
            $step = 50000;

            // Generate a random multiple of 50,000
            $randomNominal = $min + ($step * rand(0, ($max - $min) / $step));

            //random pendapatan_id
            $pendapatan = ['INTABUNGAN', 'ININFAQ'];
            $pendapatan_id = $pendapatan[array_rand($pendapatan)];

            //tanggal, carbon 3 bulan kebelakang
            $startDate = Carbon::now()->subMonths(3); // 3 months ago
            $endDate = Carbon::now(); // Today
            $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp)->format('Y-m-d H:i:s');

            //buat transaksi tabungan
            Transaksi::create([
                'nama'          => $pendapatan_id == 'INTABUNGAN' ? 'Tabungan' : 'Infaq',
                'nominal'       => $randomNominal,
                'arus'          => 'masuk',
                'user_id'       => $siswa->user_id,
                'pendapatan_id' => $pendapatan_id,
                'rekening_id'   => 'CASH',
                'keterangan'    => 'Pembayaran ' . $pendapatan_id == 'INTABUNGAN' ? 'Tabungan' : 'Infaq',
                'tanggal'       => $randomDate
            ]);

            // Log progress to terminal
            echo "Seeding random transaksi for " . $siswa->nama . " nominal: Rp " . number_format($randomNominal, 0, ',', '.') . "\n";
        });
    }
}
