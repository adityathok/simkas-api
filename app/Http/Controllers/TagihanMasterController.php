<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TagihanMaster;
use App\Models\Tagihan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TagihanMasterController extends Controller
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

        $tagihan = TagihanMaster::with('akun_pendapatan', 'akun_pengeluaran')
            ->when($date_start, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('tanggal', [$date_start, $date_end]);
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
        $request->validate([
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric',
            'type'          => 'required|min:3',
            'total_tagihan' => 'required|numeric',
            'total_nominal' => 'required|numeric',
            'keterangan'    => 'nullable|min:3',
            'due_date'      => 'nullable|date',
            'periode_start' => 'nullable|date',
            'periode_end'   => 'nullable|date',
            'akun_pendapatan_id' => 'nullable|exists:akun_pendapatans,id',
            'tahun_ajaran'      => 'nullable',
            'unit_sekolah_id'   => 'nullable|exists:unit_sekolahs,id',
            'kelas_id'          => 'nullable|exists:kelas,id',
            'user_type'         => 'nullable',
        ]);

        $tagihan = TagihanMaster::create([
            'nama'          => $request->nama,
            'nominal'       => $request->nominal,
            'type'          => $request->type,
            'total_tagihan' => $request->total_tagihan,
            'total_nominal' => $request->total_nominal,
            'keterangan'    => $request->keterangan,
            'due_date'      => $request->due_date,
            'periode_start' => $request->periode_start,
            'periode_end'   => $request->periode_end,
            'akun_pendapatan_id' => $request->akun_pendapatan_id,
            'tahun_ajaran'      => $request->tahun_ajaran,
            'unit_sekolah_id'   => $request->unit_sekolah_id,
            'kelas_id'          => $request->kelas_id,
            'user_type'         => $request->user_type,
            'admin_id'          => auth()->user()->id
        ]);

        return response()->json($tagihan);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //get tagihan
        $tagihan = TagihanMaster::with('akun_pendapatan')->find($id);
        return response()->json($tagihan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric',
            'type'          => 'required|min:3',
            'total_tagihan' => 'required|numeric',
            'total_nominal' => 'required|numeric',
            'keterangan'    => 'nullable|min:3',
            'due_date'      => 'nullable|date',
            'periode_start' => 'nullable|date',
            'periode_end'   => 'nullable|date',
            'akun_pendapatan_id' => 'nullable|exists:akun_pendapatans,id',
            'tahun_ajaran'      => 'nullable',
            'unit_sekolah_id'   => 'nullable|exists:unit_sekolahs,id',
            'kelas_id'          => 'nullable|exists:kelas,id',
            'user_type'         => 'nullable',
        ]);

        $tagihan = TagihanMaster::find($id)->update([
            'nama'          => $request->nama,
            'nominal'       => $request->nominal,
            'type'          => $request->type,
            'total_tagihan' => $request->total_tagihan,
            'total_nominal' => $request->total_nominal,
            'keterangan'    => $request->keterangan,
            'due_date'      => $request->due_date,
            'periode_start' => $request->periode_start,
            'periode_end'   => $request->periode_end,
            'akun_pendapatan_id' => $request->akun_pendapatan_id,
            // 'tahun_ajaran'      => $request->tahun_ajaran,
            // 'unit_sekolah_id'   => $request->unit_sekolah_id,
            // 'kelas_id'          => $request->kelas_id,
            // 'user_type'         => $request->user_type,
        ]);

        return response()->json($tagihan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tagihan = TagihanMaster::find($id);

        //hapus tagihan
        $tagihan->tagihans()->delete();

        $tagihan->delete();
    }

    //proses tagihan batch
    public function tagihan_batch(Request $request)
    {
        $request->validate([
            'tagihan_master_id'      => 'required|exists:tagihan_masters,id',
            'offset'                 => 'required|numeric',
            'limit'                  => 'nullable|numeric',
        ]);

        $tagihan_master_id = $request->tagihan_master_id;
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 100;

        $master = TagihanMaster::find($tagihan_master_id);

        $tahun_ajaran = $master->tahun_ajaran;
        $unit_sekolah_id = $master->unit_sekolah_id;
        $kelas_id = $master->kelas_id;
        $user_id = $master->user_id;

        //get user by kelas
        $siswa = Siswa::skip($offset)->take($limit)
            ->when($unit_sekolah_id, function ($query) use ($unit_sekolah_id) {
                return $query->whereHas('kelas', function ($query) use ($unit_sekolah_id) {
                    $query->where('unit_sekolah_id', $unit_sekolah_id);
                });
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->whereHas('kelas', function ($query) use ($kelas_id) {
                    $query->where('kelas_id', $kelas_id);
                });
            })
            ->with(['user:id,avatar,name', 'kelas'])
            ->get();

        //get total siswa
        $total_siswa = Siswa::with(['user:id,avatar,name', 'kelas'])
            ->when($unit_sekolah_id, function ($query) use ($unit_sekolah_id) {
                return $query->whereHas('kelas', function ($query) use ($unit_sekolah_id) {
                    $query->where('unit_sekolah_id', $unit_sekolah_id);
                });
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->whereHas('kelas', function ($query) use ($kelas_id) {
                    $query->where('kelas_id', $kelas_id);
                });
            })
            ->count();

        //create tagihan insert
        $data = [];
        $counter = 1 + $offset;
        foreach ($siswa as $user) {
            $count = str_pad($counter, 4, '0', STR_PAD_LEFT);
            $data[] = [
                'id'                => 'INV' . Carbon::now()->format('ymd') . $count . strtoupper(Str::random(4)),
                'nama'              => $master->nama,
                'user_id'           => $user->user_id,
                'tagihan_master_id' => $master->id,
                'tanggal'           => now(),
                'keterangan'        => null,
                'status'            => 'belum',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            $counter++;
        }
        Tagihan::insert($data);

        $totalUsers = $total_siswa;
        $nextOffset = $offset + $limit;
        $isDone     = $nextOffset >= $totalUsers;

        return response()->json([
            'next_offset'   => $nextOffset,
            'done'          => $isDone,
            'processed'     => count($siswa),
            'siswa'         => $siswa
        ]);
    }
}
