<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunRekening;
use App\Models\Transaksi;

class LikuiditasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Ambil data transaksi berdasarkan bulan dan akun_id
        $query = Transaksi::with(
            'items',
            'items.akun_pendapatan:id,nama',
            'items.akun_pengeluaran:id,nama',
            'akun_rekening:id,nama',
            'user:id,name,type',
            'user.siswa:id,nama,user_id,nis',
            'user.pegawai:id,nama,user_id',
            'admin.pegawai:id,nama,user_id'
        );

        //filter by bulan
        $bulan = $request->input('bulan') ?? null;
        if ($bulan) {
            // $query->whereMonth('tanggal', $bulan);
            // $query->where('tanggal', 'LIKE', '%' . $bulan . '%');
        }

        //filter by rekening_id
        $rekening_id = $request->input('rekening_id') ?? null;
        if ($rekening_id) {
            $query->where('akun_rekening_id', $rekening_id);
        }

        $query->orderBy('tanggal', 'desc');

        $per_page = $request->input('per_page') ?? 20;
        $transaksi = $query->paginate($per_page);

        $transaksi->withPath('/likuiditas');

        //tambahkan saldo ke setiap transaksi, saldo hasil dari penjumlahan nominal transaksi
        $saldo = 0;
        $transaksi->setCollection(
            $transaksi->getCollection()->transform(function ($item) use (&$saldo) {
                if ($item->jenis == 'pendapatan') {
                    $saldo += $item->nominal;
                } else {
                    $saldo -= $item->nominal;
                }
                $item->saldo = $saldo;
                return $item;
            })
        );

        return response()->json($transaksi);
    }
}
