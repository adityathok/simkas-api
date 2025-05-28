<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunPendapatan;
use App\Models\AkunPengeluaran;
use App\Models\Transaksi;

class LabaRugiController extends Controller
{
    public function index(string $tanggal)
    {
        $result = ['pendapatan' => [], 'pengeluaran' => []];

        //get akun pendapatan
        $akun_pendapatan = AkunPendapatan::all();
        foreach ($akun_pendapatan as $item) {
            $result['pendapatan'][$item->id] = [
                'nama' => $item->nama,
                'nominals' => [],
                'total_nominal' => 0,
            ];
        }

        $akun_pengeluaran = AkunPengeluaran::all();
        foreach ($akun_pengeluaran as $item) {
            $result['pengeluaran'][$item->id] = [
                'nama' => $item->nama,
                'nominals' => [],
                'total_nominal' => 0,
            ];
        }

        //get transaksi
        $query = Transaksi::with(
            'items',
            'items.akun_pendapatan:id,nama,neraca',
            'items.akun_pengeluaran:id,nama',
            'akun_rekening:id,nama',
        );
        $query->select('id', 'nominal');

        //get transaksi berdasarkan tanggal
        if ($tanggal) {
            //get tanggal pertama dan terakhir dari tanggal            
            $date_start = date('Y-m', strtotime($tanggal)) . '-01';
            $query->whereBetween('tanggal', [$date_start, $tanggal]);
        }

        $transaksi = $query->get();

        $masuk = 0;
        $keluar = 0;
        foreach ($transaksi as $item) {
            //loop items
            foreach ($item->items as $i) {
                $akunType = $i->akun_pendapatan_id ? 'pendapatan' : 'pengeluaran';
                $akunId = $i->akun_pendapatan_id ?? $i->akun_pengeluaran->id;
                $akunNama = $i->akun_pendapatan_id ? $i->akun_pendapatan->nama : $i->akun_pengeluaran->nama;
                $nominal = $i->nominal;

                // Inisialisasi jika belum ada
                if (!isset($result[$akunType][$akunId])) {
                    $result[$akunType][$akunId] = ['nama' => $akunNama, 'nominals' => [], 'total_nominal' => 0];
                }

                $result[$akunType][$akunId]['nominals'][] = $nominal;
                $result[$akunType][$akunId]['total_nominal'] += $nominal;

                if ($i->akun_pendapatan_id) {
                    $masuk += $nominal;
                } else {
                    $keluar += $nominal;
                }
            }
        }

        // Reset indeks array menjadi urutan integer
        $result['pendapatan'] = array_values($result['pendapatan']);
        $result['pengeluaran'] = array_values($result['pengeluaran']);

        // Menyamakan jumlah array pendapatan dan pengeluaran
        $jumlahPendapatan = count($result['pendapatan']);
        $jumlahPengeluaran = count($result['pengeluaran']);

        if ($jumlahPendapatan < $jumlahPengeluaran) {
            $selisih = $jumlahPengeluaran - $jumlahPendapatan;
            $result['pendapatan'] = array_merge($result['pendapatan'], array_fill(0, $selisih, ['nama' => 0, 'nominals' => [], 'total_nominal' => 0]));
        } elseif ($jumlahPengeluaran < $jumlahPendapatan) {
            $selisih = $jumlahPendapatan - $jumlahPengeluaran;
            $result['pengeluaran'] = array_merge($result['pengeluaran'], array_fill(0, $selisih, ['nama' => 0, 'nominals' => [], 'total_nominal' => 0]));
        }

        return response()->json([
            'raw' => $transaksi,
            'data' => $result,
            'total_pendapatan' => $masuk,
            'total_pengeluaran' => $keluar,
            'total_laba_rugi' => $masuk - $keluar,
            'count_pendapatan' => $jumlahPendapatan,
            'count_pengeluaran' => $jumlahPengeluaran,
            'count_diff' => $selisih,
        ]);
    }
}
