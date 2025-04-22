<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $total_tagihan = $master->total_tagihan;
        $type_tagihan = $master->type;

        //if type = bulanan, buat periode
        if ($type_tagihan == 'bulanan') {
            $periode_start = Carbon::createFromFormat('Y-m-d H:i:s', $master->periode_start); // Tanggal mulai
            $periode_end = Carbon::createFromFormat('Y-m-d H:i:s', $master->periode_end);   // Tanggal selesai  
        }

        //get user by kelas
        $siswa = Siswa::skip($offset)->take($limit)
            ->when($tahun_ajaran, function ($query) use ($tahun_ajaran) {
                return $query->whereHas('kelas', function ($query) use ($tahun_ajaran) {
                    $query->where('tahun_ajaran', $tahun_ajaran);
                });
            })
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
        $total_siswa = Siswa::with(['user:id,name', 'kelas'])
            ->when($tahun_ajaran, function ($query) use ($tahun_ajaran) {
                return $query->whereHas('kelas', function ($query) use ($tahun_ajaran) {
                    $query->where('tahun_ajaran', $tahun_ajaran);
                });
            })
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
        $log = [];
        $count_tagihan = 0;

        foreach ($siswa as $user) {

            // Mendapatkan key cache berdasarkan tanggal hari ini
            $cacheKey = date('ymd') . '_tagihancounter';

            //jika type = bulanan
            if ($type_tagihan == 'bulanan') {

                // loop daftar bulan
                while ($periode_start <= $periode_end) {
                    $month = $periode_start->format('m-Y'); // Format bulan

                    // Mendapatkan nilai counter dari cache, default ke 0 jika belum ada
                    $counter = Cache::get($cacheKey, 0) + 1;
                    // Simpan kembali counter ke cache
                    Cache::put($cacheKey, $counter, now()->endOfDay());
                    $count = str_pad($counter, 5, '0', STR_PAD_LEFT);
                    $inv = 'INV' . Carbon::now()->format('ymd') . $count . strtoupper(Str::random(4));

                    $tgl = $periode_start->format('Y-m');

                    $data[] = [
                        'id'                => $inv,
                        'nama'              => $master->nama,
                        'user_id'           => $user->user_id,
                        'tagihan_master_id' => $master->id,
                        'tanggal'           => $tgl . '-01 00:01:00',
                        'keterangan'        => null,
                        'status'            => 'belum',
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                    $log[] = [
                        'invoice'           => $inv,
                        'user'              => $user->nama,
                        'tagihan_master_id' => $master->id,
                    ];
                    $count_tagihan++;

                    $periode_start->addMonth(); // Tambah satu bulan
                }
            } else { //jika type = insidental

                // Mendapatkan nilai counter dari cache, default ke 0 jika belum ada
                $counter = Cache::get($cacheKey, 0) + 1;
                // Simpan kembali counter ke cache
                Cache::put($cacheKey, $counter, now()->endOfDay());
                $count = str_pad($counter, 5, '0', STR_PAD_LEFT);
                $inv = 'INV' . Carbon::now()->format('ymd') . $count . strtoupper(Str::random(4));

                $data[] = [
                    'id'                => $inv,
                    'nama'              => $master->nama,
                    'user_id'           => $user->user_id,
                    'tagihan_master_id' => $master->id,
                    'tanggal'           => now(),
                    'keterangan'        => null,
                    'status'            => 'belum',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
                $log[] = [
                    'invoice'           => $inv,
                    'user'              => $user->nama,
                    'tagihan_master_id' => $master->id,
                ];

                $count_tagihan++;
            }
        }
        Tagihan::insert($data);

        $totalUsers = $total_siswa;
        $nextOffset = $offset + $limit;
        $isDone     = $nextOffset >= $totalUsers;

        return response()->json([
            'next_offset'       => $nextOffset,
            'done'              => $isDone,
            'processed'         => count($siswa),
            'total_processed'   => $count_tagihan + $offset,
            'total_tagihan'     => $total_tagihan,
            'log'               => $log ?? [],
            // 'siswa'         => $siswa
        ]);
    }
}
