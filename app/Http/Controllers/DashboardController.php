<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tagihan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan') ?? date('m-Y');

        //hitung total Transaksi, jenis = pendapatan bulan ini
        $rekap = Transaksi::select('jenis', DB::raw('SUM(nominal) as total'))
            ->whereNull('deleted_at')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereIn('jenis', ['pendapatan', 'pengeluaran'])
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        // Ambil nilainya, default ke 0 kalau tidak ada data
        $totalPendapatan = $rekap['pendapatan'] ?? 0;
        $totalPengeluaran = $rekap['pengeluaran'] ?? 0;

        $totalToday = Transaksi::whereDate('tanggal', today())
            ->sum('nominal');

        $totalTagihan = Tagihan::with('tagihan_master')
            ->where('status', 'belum')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->get()
            ->sum(function ($tagihan) {
                return $tagihan->master->nominal ?? 0;
            });

        $tahun = now()->year;
        $data = DB::table('transaksis')
            ->selectRaw('MONTH(tanggal) as bulan, jenis, SUM(nominal) as total')
            ->whereYear('tanggal', $tahun)
            ->whereNull('deleted_at')
            ->whereIn('jenis', ['pendapatan', 'pengeluaran'])
            ->groupBy(DB::raw('MONTH(tanggal)'), 'jenis')
            ->get();

        // Siapkan array 12 bulan
        $bulanLabels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        // Inisialisasi data per bulan
        $pendapatan = array_fill(0, 12, 0);
        $pengeluaran = array_fill(0, 12, 0);

        // Isi data berdasarkan hasil query
        foreach ($data as $row) {
            $index = $row->bulan - 1; // bulan 1 jadi index 0
            if ($row->jenis === 'pendapatan') {
                $pendapatan[$index] = $row->total;
            } elseif ($row->jenis === 'pengeluaran') {
                $pengeluaran[$index] = $row->total;
            }
        }

        return response()->json([
            'total_pendapatan'      => $totalPendapatan,
            'total_pengeluaran'     => $totalPengeluaran,
            'total_hariini'         => $totalToday,
            'total_tagihan'         => $totalTagihan,
            'chart'                 => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [
                        'label' => 'Pendapatan',
                        'data' => $pendapatan,
                    ],
                    [
                        'label' => 'Pengeluaran',
                        'data' => $pengeluaran,
                    ],
                ]
            ],
            'chart_pendapatan' => $this->chart_pendapatan()
        ]);
    }

    private function chart_pendapatan()
    {
        $data = DB::table('transaksi_items')
            ->join('akun_pendapatans', function ($join) {
                $join->on('transaksi_items.akun_pendapatan_id', '=', 'akun_pendapatans.id')
                    ->whereNull('akun_pendapatans.deleted_at');
            })
            ->join('transaksis', function ($join) {
                $join->on('transaksi_items.transaksi_id', '=', 'transaksis.id')
                    ->whereNull('transaksis.deleted_at');
            })
            ->whereNull('transaksi_items.deleted_at')
            ->where('transaksis.jenis', 'pendapatan')
            ->whereMonth('transaksis.tanggal', now()->month)
            ->whereYear('transaksis.tanggal', now()->year)
            ->select('akun_pendapatans.nama as label', DB::raw('SUM(transaksi_items.nominal) as total'))
            ->groupBy('transaksi_items.akun_pendapatan_id', 'akun_pendapatans.nama')
            ->get();

        $labels = [];
        $totals = [];
        $colors = [];

        foreach ($data as $row) {
            $labels[] = $row->label;
            $totals[] = $row->total;
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [[
                'data' => $totals,
            ]]
        ];

        return $chartData;
    }
}
