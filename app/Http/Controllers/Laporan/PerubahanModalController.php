<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AkunPendapatan;
use App\Models\AkunPengeluaran;
use App\Models\AkunRekening;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\SaldoAwal;

class PerubahanModalController extends Controller
{

    public function index(string $tanggal)
    {

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
                $nominal = $i->nominal;

                if ($i->akun_pendapatan_id) {
                    $masuk += $nominal;
                } else {
                    $keluar += $nominal;
                }
            }
        }

        //modal awal, diambil dari saldo awal rekening di bulan yang sama
        $d_year = date('Y', strtotime($tanggal));
        $d_month = date('m', strtotime($tanggal));
        $modal_awal = SaldoAwal::where('bulan', $d_month)
            ->where('tahun', $d_year)
            ->sum('nominal');

        return response()->json([
            'total_pendapatan' => $masuk,
            'total_pengeluaran' => $keluar,
            'total_bersih' => $masuk - $keluar,
            'modal_awal' => (int) $modal_awal,
            'penambahan_modal' => $masuk - $keluar,
            'saldo_akhir' => $modal_awal + ($masuk - $keluar),
            'likuiditas_pendapatan' => $this->likuiditas_pendapatan($tanggal),
            'likuiditas_rekening' => $this->likuiditas_rekening($tanggal),
        ]);
    }

    private function likuiditas_pendapatan($tanggal)
    {
        $date_start = date('Y-m', strtotime($tanggal)) . '-01';

        $result = [];

        //get akun pendapatan dengan jurnal_kas => likuiditas = true
        $akun_pendapatan = AkunPendapatan::with(['jurnalkas' => function ($query) {
            $query->where('likuiditas', true);
        }], 'akunpengeluaran')
            ->whereHas('jurnalkas', function ($query) {
                $query->where('likuiditas', true);
            })
            ->get();

        foreach ($akun_pendapatan as $item) {

            $pemasukan = TransaksiItem::where('akun_pendapatan_id', $item->id)
                ->whereHas('transaksi', function ($q) use ($date_start, $tanggal) {
                    $q->whereBetween('tanggal', [$date_start, $tanggal]);
                })
                ->sum('nominal');

            $akunPengeluaranIds = $item->akunpengeluaran->pluck('id');
            $pengeluaran = TransaksiItem::whereIn('akun_pengeluaran_id', $akunPengeluaranIds)
                ->whereHas('transaksi', function ($q) use ($date_start, $tanggal) {
                    $q->whereBetween('tanggal', [$date_start, $tanggal]);
                })
                ->sum('nominal');

            $saldo = $pemasukan - $pengeluaran;

            $result[$item->id] = [
                'nama' => $item->nama,
                'total_nominal' => $saldo,
            ];
        }

        return $result;
    }

    private function likuiditas_rekening($tanggal)
    {
        $date_start = date('Y-m', strtotime($tanggal)) . '-01';
        $result = [];

        //get akun rekening dengan jurnal_kas => likuiditas = true
        $akun_rekening = AkunRekening::all();
        foreach ($akun_rekening as $item) {

            $pemasukan = Transaksi::where('akun_rekening_id', $item->id)
                ->whereBetween('tanggal', [$date_start, $tanggal])
                ->where('jenis', 'pendapatan')
                ->sum('nominal');

            $pengeluaran = Transaksi::where('akun_rekening_id', $item->id)
                ->whereBetween('tanggal', [$date_start, $tanggal])
                ->where('jenis', 'pengeluaran')
                ->sum('nominal');

            $saldo = $pemasukan - $pengeluaran;

            $result[$item->id] = [
                'nama' => $item->nama,
                'total_nominal' => $saldo,
            ];
        }

        //get transaksi
        $query = Transaksi::with(
            'akun_rekening:id,nama',
        );
        $query->select('id', 'nominal', 'akun_rekening_id');

        return $result;
    }
}
