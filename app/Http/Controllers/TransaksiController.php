<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $count = $request->input('count') ?? 20;
        $date_start = $request->input('date_start') ?? null;
        $date_end = $request->input('date_end') ?? null;

        if ($date_start) {
            $date_s   = trim($date_start, '"');
            $tgl_s    = Carbon::parse($date_s);
            $date_start = $tgl_s->format('Y-m-d 00:00:00');
        }
        if ($date_start && !$date_end) {
            $date_s   = Carbon::parse($date_start);
            $date_end = $date_s->format('Y-m-d 23:59:59');
        }
        if ($date_end) {
            $date_s   = trim($date_end, '"');
            $tgl_s    = Carbon::parse($date_s);
            $date_end = $tgl_s->format('Y-m-d 23:59:59');
        }

        //filters
        $arus = $request->input('arus') ?? null;
        $pendapatan_id = $request->input('pendapatan_id') ?? null;
        $pengeluaran_id = $request->input('pengeluaran_id') ?? null;
        $rekening_id = $request->input('rekening_id') ?? null;
        $user_id = $request->input('user_id') ?? null;

        $transaksi = Transaksi::with(
            'akunpendapatan:id,nama',
            'akunpengeluaran:id,nama',
            'akunrekening:id,nama',
            'user:id,name,type',
            'user.siswa:id,nama,user_id,nis',
            'user.pegawai:id,nama,user_id',
            'admin.pegawai:id,nama,user_id'
        )
            ->when($date_start, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('tanggal', [$date_start, $date_end]);
            })
            ->when($arus, function ($query) use ($arus) {
                return $query->where('arus', $arus);
            })
            ->when($pendapatan_id, function ($query) use ($pendapatan_id) {
                return $query->where('pendapatan_id', $pendapatan_id);
            })
            ->when($pengeluaran_id, function ($query) use ($pengeluaran_id) {
                return $query->where('pengeluaran_id', $pengeluaran_id);
            })
            ->when($rekening_id, function ($query) use ($rekening_id) {
                return $query->where('rekening_id', $rekening_id);
            })
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate($count);
        $transaksi->withPath('/transaksi');

        return response()->json($transaksi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric',
            'arus'          => 'required|in:masuk,keluar',
            'pendapan_id'   => 'nullable|exists:akun_pendapatans,id',
            'pengeluaran_id' => 'nullable|exists:akun_pengeluarans,id',
            'rekening_id'   => 'nullable|exists:akun_rekenings,id',
            'keterangan'    => 'nullable',
            'user_id'       => 'nullable|exists:users,id',
            'tanggal'       => 'nullable|date',
        ]);

        $transaksi = Transaksi::create([
            'nama'          => $request->nama,
            'nominal'       => $request->nominal,
            'arus'          => $request->arus,
            'pendapatan_id' => $request->arus == 'masuk' ? $request->pendapatan_id : null,
            'pengeluaran_id' => $request->arus == 'keluar' ? $request->pengeluaran_id : null,
            'rekening_id'   => $request->rekening_id ?? 'CASH',
            'user_id'       => $request->user_id,
            'admin_id'      => auth()->user()->id,
            'keterangan'    => $request->keterangan,
            'tanggal'       => $request->tanggal ?? null,
        ]);

        return response()->json($transaksi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric',
            'arus'          => 'required|in:masuk,keluar',
            'pendapan_id'   => 'nullable|exists:akun_pendapatans,id',
            'pengeluaran_id' => 'nullable|exists:akun_pengeluarans,id',
            'rekening_id'   => 'nullable|exists:akun_rekenings,id',
            'keterangan'    => 'nullable',
            'user_id'       => 'nullable|exists:users,id',
            'tanggal'       => 'nullable|date',
        ]);

        //temukan transaksi
        $transaksi = Transaksi::find($id);

        //update
        $transaksi->update([
            'nama'          => $request->nama,
            'nominal'       => $request->nominal,
            'arus'          => $request->arus,
            'pendapatan_id' => $request->arus == 'masuk' ? $request->pendapatan_id : null,
            'pengeluaran_id' => $request->arus == 'keluar' ? $request->pengeluaran_id : null,
            'rekening_id'   => $request->rekening_id ?? 'CASH',
            'user_id'       => $request->user_id,
            'keterangan'    => $request->keterangan,
            'tanggal'       => $request->tanggal ?? null,
        ]);
        return response()->json($transaksi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
