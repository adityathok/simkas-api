<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas:id,nama,tahun_ajaran', 'user:id,name,avatar');

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->input('cari') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $siswa = $query->paginate(20);
        $siswa->withPath('/siswa');

        $siswa->getCollection()->transform(function ($s) {
            $kelasAktif = $s->kelas->where('pivot.active', true)->first();

            return [
                'id'            => $s->id,
                'nama'          => $s->nama ?? null,
                'nis'           => $s->nis,
                'nisn'          => $s->nisn ?? null,
                'jenis_kelamin' => $s->jenis_kelamin,
                'ttl'           => $s->tempat_lahir . ',' . $s->tanggal_lahir,
                'kelas'         => $kelasAktif ? $kelasAktif->nama : null,
                'avatar_url'    => $s->avatar_url,
                'status'        => $s->status,
            ];
        });

        return response()->json($siswa);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'               => 'required|min:5|numeric',
            'nisn'              => 'nullable|min:5|numeric',
            'nama'              => 'required|min:3',
            'nama_panggilan'    => 'required|min:3',
            'status'            => 'required|min:3',
            'tempat_lahir'      => 'required|min:3',
            'tanggal_lahir'     => 'required|min:3',
            'tanggal_masuk'     => 'required|min:3',
            'jenis_kelamin'     => 'required|min:3',
            'email'             => 'nullable|min:10|email',
        ]);

        $siswa = Siswa::create([
            'nis'               => $request->nis,
            'nisn'              => $request->nisn,
            'nama'              => $request->nama,
            'nama_panggilan'    => $request->nama_panggilan,
            'status'            => $request->status,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'tanggal_masuk'     => $request->tanggal_masuk,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'email'             => $request->email
        ]);

        return response()->json($siswa);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with(['user:id,avatar'])->find($id);

        if (!$siswa) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        return response()->json($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nis'               => 'required|min:5|numeric',
            'nisn'              => 'nullable|min:5|numeric',
            'nama'              => 'required|min:3',
            'nama_panggilan'    => 'required|min:3',
            'status'            => 'required|min:3',
            'tempat_lahir'      => 'required|min:3',
            'tanggal_lahir'     => 'required|min:3',
            'tanggal_masuk'     => 'required|min:3',
            'jenis_kelamin'     => 'required|min:3',
            'email'             => 'nullable|min:10|email',
        ]);

        $siswa = Siswa::find($id);
        $siswa->update([
            'nis'               => $request->nis,
            'nisn'              => $request->nisn,
            'nama'              => $request->nama,
            'nama_panggilan'    => $request->nama_panggilan,
            'status'            => $request->status,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'tanggal_masuk'     => $request->tanggal_masuk,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'email'             => $request->email
        ]);

        return response()->json($siswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);
        $siswa->delete();
    }


    /**
     * Cari siswa dari nama / nis.
     */
    public function search(Request $request)
    {
        $request->validate([
            'cari' => 'required|min:3',
        ]);
        $cari = $request->cari;

        // Cari berdasarkan nama / nis 
        // Gunakan caching dengan waktu 5 menit (300 detik)
        $siswa = Cache::remember('siswa_search_' . $cari, 5, function () use ($cari) {
            return Siswa::select('id', 'nama', 'user_id', 'nis', 'status')
                ->where('nama', 'like', '%' . $cari . '%')
                ->orWhere('nis', 'like', '%' . $cari . '%')
                ->with(['user:id,avatar', 'kelasAktif:nama'])
                ->get();
        });

        if (empty($siswa) || $siswa->isEmpty()) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        return response()->json($siswa);
    }

    //cari siswa berdasarkan user_id    
    public function searchbyuserid(Request $request)
    {
        $request->validate([
            'user_id' => 'required|min:3',
        ]);
        $user_id = $request->user_id;

        $siswa = Siswa::select('id', 'nama', 'user_id', 'nis', 'status')
            ->where('user_id', $user_id)
            ->with(['user:id,avatar', 'kelasAktif:nama'])
            ->get();

        if (empty($siswa) || $siswa->isEmpty()) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        return response()->json($siswa);
    }

    public function count_siswa(Request $request)
    {

        $request->validate([
            'tahun_ajaran' => 'nullable|min:3',
            'kelas_id' => 'nullable|min:3',
            'unit_sekolah_id' => 'nullable|min:3',
        ]);

        $tahun_ajaran = $request->tahun_ajaran ?? TahunAjaran::getActive()->nama;
        $unit = $request->unit_sekolah_id ?? null;

        $kelas = Kelas::where('tahun_ajaran', $tahun_ajaran)
            ->when($unit, function ($query) use ($unit) {
                return $query->where('unit_sekolah_id', $unit);
            })
            ->when($request->kelas_id, function ($query) use ($request) {
                return $query->where('id', $request->kelas_id);
            })
            ->withCount('siswaKelas')
            ->orderBy('nama', 'asc')
            ->get();

        $total = $kelas->sum('siswa_kelas_count');

        //collection
        $kelas = collect($kelas)->map(function ($data) {
            return [
                'id'            => $data->id,
                'name'          => $data->nama,
                'tahun_ajaran'  => $data->tahun_ajaran,
                'siswa_count'   => $data->siswa_kelas_count
            ];
        });

        return response()->json([
            'data' => $kelas,
            'total' => $total
        ]);
    }
}
