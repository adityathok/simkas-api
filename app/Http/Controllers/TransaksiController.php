<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $per_page = $request->input('per_page') ?? 20;

        //get transaksi
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

        //filter tanggal
        if ($date_start && $date_end) {
            $query->whereBetween('tanggal', [$date_start, $date_end]);
        }

        //filter by user_id
        $user_id = $request->input('user_id') ?? null;
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        //filter by jenis
        $jenis = $request->input('jenis') ?? null;
        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        //filter by rekening_id
        $rekening_id = $request->input('rekening_id') ?? null;
        if ($rekening_id) {
            $query->where('akun_rekening_id', $rekening_id);
        }

        //filter by items.akun_pendapatan_id
        $pendapatan_id = $request->input('pendapatan_id') ?? null;
        if ($pendapatan_id) {
            $query->whereHas('items', function ($query) use ($pendapatan_id) {
                $query->where('akun_pendapatan_id', $pendapatan_id);
            });
        }

        //filter by items.akun_pengeluaran_id
        $pengeluaran_id = $request->input('pengeluaran_id') ?? null;
        if ($pengeluaran_id) {
            $query->whereHas('items', function ($query) use ($pengeluaran_id) {
                $query->where('akun_pengeluaran_id', $pengeluaran_id);
            });
        }

        $query->orderBy('tanggal', 'desc');
        $transaksi = $query->paginate($per_page);
        $transaksi->withPath('/transaksi');

        return response()->json($transaksi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nominal'                   => 'required|numeric',
            'jenis'                     => 'required|in:pendapatan,pengeluaran,transfer',
            'tanggal'                   => 'nullable|date',
            'user_id'                   => 'required|min:3',
            'akun_rekening_id'          => 'required|exists:akun_rekenings,id',
            'akun_rekening_tujuan_id'   => 'nullable|exists:akun_rekenings,id',
            'metode_pembayaran'         => 'nullable|in:tunai,transfer',
            'status'                    => 'required',
            'catatan'                   => 'nullable',
            'items'                     => 'nullable|array',
            'nama'                      => 'nullable',
        ]);

        $transaksi = Transaksi::create([
            'nominal'                   => $request->nominal,
            'jenis'                     => $request->jenis,
            'tanggal'                   => $request->tanggal ?? Carbon::now(),
            'user_id'                   => $request->user_id,
            'akun_rekening_id'          => $request->akun_rekening_id ?? 1,
            'akun_rekening_tujuan_id'   => $request->akun_rekening_tujuan_id,
            'metode_pembayaran'         => $request->metode_pembayaran ?? 'tunai',
            'status'                    => $request->status ?? 'sukses',
            'admin_id'                  => auth()->user()->id,
            'catatan'                   => $request->catatan,
        ]);

        //simpan TransaksiItem
        $items = $request->items;
        if ($items) {
            foreach ($items as $item) {
                $tagihan_id = $item['tagihan_id'] ?? null;
                TransaksiItem::create([
                    'transaksi_id'          => $transaksi->id,
                    'nama'                  => $item['nama'],
                    'qty'                   => $item['qty'] ?? 1,
                    'nominal'               => $item['nominal'],
                    'nominal_item'          => $item['nominal_item'] ?? $item['nominal'],
                    'tagihan_id'            => $tagihan_id,
                    'akun_pendapatan_id'    => $request->jenis == 'pendapatan' ? $request->akun_pendapatan_id : null,
                    'akun_pengeluaran_id'   => $request->jenis == 'pengeluaran' ? $request->akun_pengeluaran_id : null,
                ]);

                //jika ada tagihan_id , ubah status tagihan
                //get tagihan by id
                $tagihan = Tagihan::find($tagihan_id);
                if ($tagihan) {
                    $tagihan->update([
                        'status' => 'lunas',
                    ]);
                }
            }
        } else if (!$items && $request->nama) {
            TransaksiItem::create([
                'transaksi_id'          => $transaksi->id,
                'nama'                  => $request->nama,
                'qty'                   => 1,
                'nominal'               => $request->nominal,
                'nominal_item'          => $request->nominal,
                'tagihan_id'            => null,
                'akun_pendapatan_id'    => $request->jenis == 'pendapatan' ? $request->akun_pendapatan_id : null,
                'akun_pengeluaran_id'   => $request->jenis == 'pengeluaran' ? $request->akun_pengeluaran_id : null,
            ]);
        }

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
            // 'nominal'                   => 'required|numeric',
            // 'jenis'                     => 'required|in:pendapatan,pengeluaran,transfer',
            // 'tanggal'                   => 'nullable|date',
            // 'user_id'                   => 'required|min:3',
            // 'akun_rekening_id'          => 'required|exists:akun_rekenings,id',
            // 'akun_rekening_tujuan_id'   => 'nullable|exists:akun_rekenings,id',
            // 'metode_pembayaran'         => 'nullable|in:tunai,transfer',
            'status'                    => 'required',
            'catatan'                   => 'nullable',
            // 'items'                     => 'nullable|array',
            // 'nama'                      => 'nullable',
        ]);

        //temukan transaksi
        $transaksi = Transaksi::find($id);

        //update
        $transaksi->update([
            // 'nominal'                   => $request->nominal,
            // 'jenis'                     => $request->jenis,
            // 'tanggal'                   => $request->tanggal ?? Carbon::now(),
            // 'user_id'                   => $request->user_id,
            // 'akun_rekening_id'          => $request->akun_rekening_id ?? 1,
            // 'akun_rekening_tujuan_id'   => $request->akun_rekening_tujuan_id,
            // 'metode_pembayaran'         => $request->metode_pembayaran ?? 'tunai',
            'status'                    => $request->status ?? 'sukses',
            'admin_id'                  => auth()->user()->id,
            'catatan'                   => $request->catatan,
        ]);
        return response()->json($transaksi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get transaksi
        $transaksi = Transaksi::find($id);

        //get items
        $items = $transaksi->items;
        //loop items
        foreach ($items as $item) {
            //jika ada tagihan_id, ubah status tagihan
            //get tagihan by id
            $tagihan = Tagihan::find($item->tagihan_id);
            if ($tagihan) {
                $tagihan->update([
                    'status' => 'belum',
                ]);
            };

            //hapus item
            $item->delete();
        }

        //hapus transaksi
        $transaksi->delete();
    }
}
