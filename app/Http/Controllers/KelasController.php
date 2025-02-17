<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitSekolah;
use App\Models\Kelas;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelas::with('unitSekolah', 'wali.pegawai');

        if ($request->filled('tahun_mulai') && $request->filled('tahun_selesai')) {
            $query->where('tahun_ajaran', $request->input('tahun_mulai') . '/' . $request->input('tahun_selesai'));
        }

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->input('cari') . '%');
        }

        if ($request->filled('unit')) {
            $query->where('unit_sekolah_id', $request->input('unit'));
        }

        $kelas = $query->paginate(20);
        $kelas->withPath('/kelas');

        // Meringkas hasil wali saja menggunakan collection map
        $kelas->getCollection()->transform(function ($item) {
            return [
                'id'            => $item->id,
                'nama'          => $item->nama,
                'tingkat'       => $item->tingkat,
                'tahun_ajaran'  => $item->tahun_ajaran,
                'unit'          => $item->unitSekolah->nama ?? null,
                'unit_id'       => $item->unitSekolah->id ?? null,
                'wali'          => $item->wali->pegawai->nama ?? null,
                'wali_id'       => $item->wali->id ?? null,
            ];
        });

        return response()->json($kelas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required|min:1',
            'tingkat'           => 'required|min:1',
            'tahun_ajaran'      => 'required|min:4',
            'unit_sekolah_id'   => 'required|min:3',
            'wali_id'           => 'required|min:3'
        ]);

        return Kelas::create([
            'nama'              => $request->nama,
            'tingkat'           => $request->tingkat,
            'tahun_ajaran'      => $request->tahun_ajaran,
            'unit_sekolah_id'   => $request->unit_sekolah_id,
            'wali_id'           => $request->wali_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelas = Kelas::with('unitSekolah', 'wali.pegawai', 'users.siswa')->find($id);

        if ($kelas == null) {
            return response()->json(['message' => 'kelas not found'], 404);
        }

        // Meringkas hasil siswa menggunakan collection map
        $siswas = $kelas->users->map(function ($user) {
            return [
                'user_id'       => $user->id,
                'nama'          => $user->siswa->nama ?? null,
                'siswa_id'      => $user->siswa->id ?? null,
                'nis'           => $user->siswa->nis ?? null,
                'nisn'          => $user->siswa->nisn ?? null,
                'avatar_url'    => $user->siswa->avatar_url ?? null
            ];
        });
        $kelas->siswa = $siswas;

        return response()->json($kelas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'              => 'required|min:1',
            'tingkat'           => 'required|min:1',
            'tahun_ajaran'      => 'required|min:4',
            'unit_sekolah_id'   => 'required|min:3',
            'wali_id'           => 'required|min:3'
        ]);

        $kelas = Kelas::find($id);
        $kelas->update([
            'nama'              => $request->nama,
            'tingkat'           => $request->tingkat,
            'tahun_ajaran'      => $request->tahun_ajaran,
            'unit_sekolah_id'   => $request->unit_sekolah_id,
            'wali_id'           => $request->wali_id
        ]);

        return response()->json($kelas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::find($id);
        $kelas->delete();
    }
}
