<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use Carbon\Carbon;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $count = $request->input('count') ?? 20;
        $date_start = $request->input('date_start') ?? null;
        $date_end = $request->input('date_end') ?? null;
        $status = $request->input('status') ?? null;
        $user_id = $request->input('user_id') ?? null;

        if ($date_start) {
            // $date_s   = trim($date_start, '"');
            $tgl_s    = Carbon::parse($date_start);
            $date_start = $tgl_s->format('Y-m-d 00:00:00');
        }

        if ($date_end) {
            // $date_s   = trim($date_end, '"');
            $tgl_s    = Carbon::parse($date_end);
            $date_end = $tgl_s->format('Y-m-d 23:59:59');
        }

        if ($date_start && !$date_end) {
            $date_s   = Carbon::parse($date_start);
            $date_end = $date_s->format('Y-m-d 23:59:59');
        }

        if (!$date_start && !$date_end) {
            //today
            $date_end = Carbon::now()->format('Y-m-d 23:59:59');
        }

        $tagihan = Tagihan::with(
            'tagihan_master:id,nama,akun_pendapatan_id,nominal,keterangan,admin_id',
            'tagihan_master.akunpendapatan:id,nama',
            'tagihan_master.admin:id,name',
            'transaksi',
            'user:id,name,type',
            'user.siswa:id,nama,user_id,nis',
            'user.pegawai:id,nama,user_id'
        )
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('tanggal', [$date_start, $date_end]);
            })
            ->when($date_start && !$date_end, function ($query) use ($date_start) {
                return $query->where('tanggal', '>=', $date_start);
            })
            ->when(!$date_start && $date_end, function ($query) use ($date_end) {
                return $query->where('tanggal', '<=', $date_end);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($count);
        $tagihan->withPath('/tagihan');

        return response()->json($tagihan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        //get tagihan
        $tagihan = Tagihan::with(
            'tagihan_master',
            'tagihan_master.akunpendapatan:id,nama',
            'transaksi',
            'user:id,name,type',
            'user.siswa:id,nama,user_id,nis',
            'user.pegawai:id,nama,user_id',
        )
            ->find($id);
        return response()->json($tagihan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status'        => 'required|in:belum,lunas,batal',
            'keterangan'    => 'nullable|string',
            'user_id'       => 'required',
            'tanggal'       => 'required|date',
        ]);
        //get tagihan
        $tagihan = Tagihan::find($id);
        $tagihan->status = $request->status;
        $tagihan->keterangan = $request->keterangan;
        $tagihan->user_id = $request->user_id;
        $tagihan->tanggal = $request->tanggal;
        $tagihan->save();

        return response()->json($tagihan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get tagihan
        $tagihan = Tagihan::find($id);
        $tagihan->delete();
    }
}
