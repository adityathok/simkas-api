<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiItem;

class LaporanJurnalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal;
        if ($tanggal) {
            $date_start = date('Y-m', strtotime($tanggal)) . '-01' . ' 00:00:00';
            $date_end = date('Y-m-d', strtotime($tanggal)) . ' 23:59:59';
        }

        //get transaksi item
        $query = TransaksiItem::with(
            'akun_pendapatan:id,nama',
            'akun_pengeluaran:id,nama',
            'transaksi',
            'transaksi.akun_rekening:id,nama',
            'transaksi.user:id,name,type',
            'transaksi.user.siswa:id,nama,user_id,nis',
            'transaksi.user.pegawai:id,nama,user_id',
            'transaksi.admin.pegawai:id,nama,user_id'
        )->whereHas('transaksi') // pastikan hanya yang memiliki transaksi
            ->join('transaksis', 'transaksi_items.transaksi_id', '=', 'transaksis.id')
            ->when($tanggal, function ($q) use ($date_start, $date_end) {
                $q->whereBetween('transaksis.tanggal', [$date_start, $date_end]);
            })
            ->orderBy('transaksis.tanggal', 'desc') // urut berdasarkan tanggal transaksi
            ->select('transaksi_items.*'); // penting agar hasilnya tetap instance TransaksiItem

        //filter akun_pendapatan.jurnalkas
        $jurnal_id = $request->jurnal_id;
        if ($jurnal_id) {
            $query->where(function ($q) use ($jurnal_id) {
                // Filter jika jurnalkas_id cocok di akun_pendapatan
                $q->whereHas('akun_pendapatan', function ($sub) use ($jurnal_id) {
                    $sub->where('jurnalkas_id', $jurnal_id);
                })
                    // ATAU cocok di akun_pengeluaran -> akun_pendapatan
                    ->orWhereHas('akun_pengeluaran.akun_pendapatan', function ($sub) use ($jurnal_id) {
                        $sub->where('jurnalkas_id', $jurnal_id);
                    });
            });
        }

        $per_page = $request->input('per_page') ?? 20;
        $transaksi = $query->paginate($per_page);
        $transaksi->withPath('/transaksi');

        return response()->json($transaksi);
    }
}
