<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunRekening;
use App\Models\Transaksi;
use App\Models\SaldoAwal;
use Carbon\Carbon;

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
        $date = Carbon::createFromFormat('Y-m', $bulan);
        if ($bulan) {

            $query->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month);
        }

        //filter by rekening_id
        $rekening_id = $request->input('rekening_id') ?? null;
        if ($rekening_id) {
            $query->where('akun_rekening_id', $rekening_id);
        }

        $per_page = $request->input('per_page') ?? 20;

        // Hitung offset
        $page = $request->input('page') ?? 1;
        $offset = ($page - 1) * $per_page;

        //get saldo awal
        $saldoAwal = SaldoAwal::where('akun_rekening_id', $rekening_id)
            ->where('bulan', $date->month)
            ->where('tahun', $date->year)
            ->first();

        //hitung saldo
        $saldo_awal = $saldoAwal ? $saldoAwal->nominal : 0;
        $count = $query->count();
        $sumPendapatan = 0;
        $sumPengeluaran = 0;
        $transaksi_akhir = [];

        if ($offset < ($count - $per_page)) {

            //ambil data transaksi awal sampai offset
            $transaksi_akhir = (clone $query)->orderBy('tanggal', 'asc')->take($count - ($per_page * $page))->get();

            $sumPendapatan = $transaksi_akhir
                ->where('jenis', 'pendapatan')
                ->sum('nominal');

            $sumPengeluaran = $transaksi_akhir
                ->where('jenis', 'pengeluaran')
                ->sum('nominal');

            $saldo_awal += $sumPendapatan - $sumPengeluaran;
        }

        //hitung total saldo rekening
        $saldo_rekening = $saldoAwal ? $saldoAwal->nominal : 0;
        $transaksi_all = (clone $query)->orderBy('tanggal', 'asc')->get();
        $sumPendapatanAll = $transaksi_all
            ->where('jenis', 'pendapatan')
            ->sum('nominal');
        $sumPengeluaranAll = $transaksi_all
            ->where('jenis', 'pengeluaran')
            ->sum('nominal');
        $saldo_rekening += $sumPendapatanAll - $sumPengeluaranAll;

        $query->orderBy('tanggal', 'desc');
        $transaksi = $query->paginate($per_page);
        $transaksi->withPath('/likuiditas');

        // Balik urutan jadi ASC hanya untuk proses hitung saldo
        $transaksi_asc = $query->get()->sortBy('tanggal')->values();
        //hitung saldo tiap transaksi
        $saldo = $saldo_awal ?? 0;
        $saldo_items = [];
        foreach ($transaksi_asc as $key => $value) {
            if ($value->jenis == 'pendapatan') {
                $saldo += $value->nominal;
            } else {
                $saldo -= $value->nominal;
            }
            $saldo_items[$value->nomor] = $saldo;
        }

        //tambahkan saldo ke setiap transaksi, saldo hasil dari penjumlahan nominal transaksi
        $transaksi->setCollection(
            $transaksi->getCollection()->transform(function ($item) use (&$saldo, $saldo_items) {
                $item->saldo = $saldo_items[$item->nomor];
                return $item;
            })
        );

        // Ubah ke array + tambahkan key ringkasan
        $response = $transaksi->toArray();
        $response['saldo_akhir'] = $saldo;
        $response['saldo_awal'] = $saldo_awal;
        $response['data_saldoawal'] = $saldoAwal ? $saldoAwal->nominal : 0;
        $response['offset'] = $offset;
        $response['sum_pendapatan'] = $sumPendapatan;
        $response['sum_pengeluaran'] = $sumPengeluaran;
        $response['rekening'] = null;
        $response['saldo_rekening'] = $saldo_rekening;

        //filter by rekening_id
        $rekening_id = $request->input('rekening_id') ?? null;
        if ($rekening_id) {
            //get data rekening
            $rekening = AkunRekening::find($rekening_id);
            $response['rekening'] = $rekening;
        }

        return response()->json($response);
    }

    //save saldo awal
    public function store_saldo_awal(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string',
            'nominal' => 'required|numeric',
            'akun_rekening_id' => 'required|integer'
        ]);
        $bulan = explode('-', $request->bulan);

        //create or replace
        $saldo_awal = SaldoAwal::updateOrCreate(
            [
                'bulan' => $bulan[1],
                'tahun' => $bulan[0],
                'akun_rekening_id' => $request->akun_rekening_id
            ],
            ['nominal' => $request->nominal]
        );

        return response()->json($saldo_awal);
    }
}
