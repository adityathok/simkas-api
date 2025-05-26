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

        $result = [];

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
                //cek apakah akun pendapatan ada
                if ($i->akun_pendapatan_id) {
                    $result['pendapatan'][$i->akun_pendapatan->id]['nama'] = $i->akun_pendapatan->nama;
                    $result['pendapatan'][$i->akun_pendapatan->id]['nominals'][] = $i->nominal;
                    $result['pendapatan'][$i->akun_pendapatan->id]['total_nominal'] = array_sum($result['pendapatan'][$i->akun_pendapatan->id]['nominals']);
                    $masuk += $i->nominal;
                } else {
                    $result['pengeluaran'][$i->akun_pengeluaran->id]['nama'] = $i->akun_pengeluaran->nama;
                    $result['pengeluaran'][$i->akun_pengeluaran->id]['nominals'][] = $i->nominal;
                    $result['pengeluaran'][$i->akun_pengeluaran->id]['total_nominal'] = array_sum($result['pengeluaran'][$i->akun_pengeluaran->id]['nominals']);
                    $keluar += $i->nominal;
                }
            }
        }

        return response()->json([
            'raw' => $transaksi,
            'data' => $result,
            'total_pendapatan' => $masuk,
            'total_pengeluaran' => $keluar,
            'total_laba_rugi' => $masuk - $keluar,
        ]);
    }
}
