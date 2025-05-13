<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\AkunPengeluaran;
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
            $transaksi = Transaksi::create([
                'nominal'               => $randomNominal,
                'jenis'                 => 'pendapatan',
                'user_id'               => $siswa->user_id,
                'akun_rekening_id'      => 1,
                'catatan'               => 'Pembayaran ' . $pendapatan_id == 'INTABUNGAN' ? 'Tabungan' : 'Infaq',
                'tanggal'               => $randomDate,
                'status'                => 'sukses',
                'admin_id'              => 10000,
            ]);
            //buat transaksi item
            TransaksiItem::create([
                'transaksi_id'          => $transaksi->id,
                'nama'                  => $pendapatan_id == 'INTABUNGAN' ? 'Tabungan' : 'Infaq',
                'qty'                   => 1,
                'nominal_item'          => $randomNominal,
                'nominal'               => $randomNominal,
                'akun_pendapatan_id'    => $pendapatan_id,
            ]);

            // Log progress to terminal
            echo "Seeding transaksi pemasukan for " . $siswa->nama . " nominal: Rp " . number_format($randomNominal, 0, ',', '.') . "\n";
        });

        //get all pegawai
        $pegawais = Pegawai::all();

        //jika tidak ada data pegawai
        if ($pegawais->isEmpty()) {
            $this->command->warn('Tidak ada data pegawai.');
            return;
        }
        //total pegawai
        $totalPegawai = $pegawais->count();
        $this->command->info('Membuat transaksi untuk ' . $totalPegawai . ' pegawai.');

        //buat transaksi untuk setiap pegawai
        $pegawais->each(function ($pegawai) {

            //random nominal
            $min = 50000;
            $max = 1000000;
            $step = 50000;
            // Generate a random multiple of 50,000
            $randomNominal = $min + ($step * rand(0, ($max - $min) / $step));

            //ambil acak dari AkunPengeluaran
            $pengeluaran = AkunPengeluaran::inRandomOrder()->first();

            //tanggal, carbon 3 bulan kebelakang
            $startDate = Carbon::now()->subMonths(3); // 3 months ago
            $endDate = Carbon::now(); // Today
            $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp)->format('Y-m-d H:i:s');

            //buat transaksi
            $transaksi = Transaksi::create([
                'nominal'               => $randomNominal,
                'jenis'                 => 'pengeluaran',
                'user_id'               => $pegawai->user_id,
                'akun_rekening_id'      => 2,
                'catatan'               => 'Pembayaran ' . $pengeluaran->nama,
                'tanggal'               => $randomDate,
                'status'                => 'sukses',
                'admin_id'              => 10000,
            ]);
            //buat transaksi item
            TransaksiItem::create([
                'transaksi_id'          => $transaksi->id,
                'nama'                  => $pengeluaran->nama,
                'qty'                   => 1,
                'nominal_item'          => $randomNominal,
                'nominal'               => $randomNominal,
                'akun_pengeluaran_id'   => $pengeluaran->id,
            ]);

            // Log progress to terminal
            echo "Seeding transaksi pengeluaran for " . $pegawai->nama . " nominal: Rp " . number_format($randomNominal, 0, ',', '.') . "\n";
        });
    }
}
