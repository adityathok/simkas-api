<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunPendapatan;
use App\Models\AkunPengeluaran;
use App\Models\Transaksi;

class NeracaController extends Controller
{
    public function index(string $bulan)
    {

        $result = [];

        //get akun pendapatan dengan neraca = true
        $akun_pendapatan = AkunPendapatan::where('neraca', true)->get();
        foreach ($akun_pendapatan as $item) {
            $result['pendapatan'][$item->id] = [
                'nama' => $item->nama,
                'nominals' => [],
                'total_nominal' => 0,
            ];
        }

        //get akun pengeluaran dengan sumber akun_pendapatan => neraca = true
        $akun_pengeluaran = AkunPengeluaran::with(['akun_pendapatan' => function ($query) {
            $query->where('neraca', true);
        }])
            ->whereHas('akun_pendapatan', function ($query) {
                $query->where('neraca', true);
            })
            ->get();
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

        //get transaksi berdasarkan bulan
        if ($bulan) {
            $bln = explode('-', $bulan);
            $query->whereYear('tanggal',  $bln[0])
                ->whereMonth('tanggal',  $bln[1]);
        }

        $transaksi = $query->get();

        $masuk = 0;
        $keluar = 0;
        foreach ($transaksi as $item) {
            //loop items
            foreach ($item->items as $i) {

                $akunId = $i->akun_pendapatan_id ?? $i->akun_pengeluaran_id;

                //cek apakah akun pendapatan ada
                if ($i->akun_pendapatan_id) {

                    //jika akunId tidak ada
                    if (!isset($result['pendapatan'][$i->akun_pendapatan_id])) {
                        continue;
                    }

                    $result['pendapatan'][$i->akun_pendapatan_id]['nama'] = $i->akun_pendapatan->nama;
                    $result['pendapatan'][$i->akun_pendapatan_id]['nominals'][] = $i->nominal;
                    $result['pendapatan'][$i->akun_pendapatan_id]['total_nominal'] = array_sum($result['pendapatan'][$i->akun_pendapatan_id]['nominals']);
                    $masuk += $i->nominal;
                } else if ($i->akun_pengeluaran_id) {

                    //jika akunId tidak ada
                    if (!isset($result['pengeluaran'][$i->akun_pengeluaran_id])) {
                        continue;
                    }

                    $result['pengeluaran'][$i->akun_pengeluaran_id]['nama'] = $i->akun_pengeluaran->nama ?? '';
                    $result['pengeluaran'][$i->akun_pengeluaran_id]['nominals'][] = $i->nominal;
                    $result['pengeluaran'][$i->akun_pengeluaran_id]['total_nominal'] = array_sum($result['pengeluaran'][$i->akun_pengeluaran_id]['nominals']);
                    $keluar += $i->nominal;
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
        ]);
    }
}
