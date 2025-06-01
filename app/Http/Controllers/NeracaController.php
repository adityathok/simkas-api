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
                //cek apakah akun pendapatan ada
                if ($i->akun_pendapatan_id) {
                    //jika neraca = false, skip
                    if (!$i->akun_pendapatan->neraca) {
                        continue;
                    }
                    $result['pendapatan'][$i->akun_pendapatan_id]['nama'] = $i->akun_pendapatan->nama;
                    $result['pendapatan'][$i->akun_pendapatan_id]['nominals'][] = $i->nominal;
                    $result['pendapatan'][$i->akun_pendapatan_id]['total_nominal'] = array_sum($result['pendapatan'][$i->akun_pendapatan_id]['nominals']);
                    $masuk += $i->nominal;
                } else {
                    $result['pengeluaran'][$i->akun_pengeluaran_id]['nama'] = $i->akun_pengeluaran->nama ?? '';
                    $result['pengeluaran'][$i->akun_pengeluaran_id]['nominals'][] = $i->nominal;
                    $result['pengeluaran'][$i->akun_pengeluaran_id]['total_nominal'] = array_sum($result['pengeluaran'][$i->akun_pengeluaran_id]['nominals']);
                    $keluar += $i->nominal;
                }
            }
        }

        return response()->json([
            'raw' => $transaksi,
            'data' => $result,
            'total_pendapatan' => $masuk,
            'total_pengeluaran' => $keluar,
        ]);
    }

    public function akun(Request $request)
    {
        //get akun pendapatan dengan neraca = true
        $akun_pendapatan = AkunPendapatan::where('neraca', true)->get();
        $akun_pengeluaran = AkunPengeluaran::all();

        return response()->json([
            'akun_pendapatan' => $akun_pendapatan,
            'akun_pengeluaran' => $akun_pengeluaran,
        ]);
    }
}
