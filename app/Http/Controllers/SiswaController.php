<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas:id,nama,tahun_ajaran');

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->input('cari') . '%');
        }

        $siswa = $query->paginate(20);
        $siswa->withPath('/siswa');

        $siswa->getCollection()->transform(function ($s) {
            $kelasAktif = $s->kelas->where('pivot.active', true)->first();

            return [
                'id'            => $s->id,
                'nama'          => $s->nama,
                'nis'           => $s->nis,
                'nisn'          => $s->nisn,
                'nama'          => $s->nama,
                'jenis_kelamin' => $s->jenis_kelamin,
                'ttl'           => $s->tempat_lahir . ',' . $s->tanggal_lahir,
                'kelas'         => $kelasAktif->nama,
            ];
        });

        return response()->json($siswa);
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
        $siswa = Siswa::find($id);

        $siswa->kelas = $siswa->kelasAktif();

        return response()->json($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
