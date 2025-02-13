<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SiswaKelas;

class SiswaKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //request GET
        $id_siswa = $request->input('id_siswa');

        if ($id_siswa) {

            $siswaArray = Siswa::with('kelas', 'kelas.unitSekolah', 'kelas.wali')->find($id_siswa);

            if ($siswaArray == null || $siswaArray->kelas == null) {
                return response()->json(['message' => 'siswa not found'], 404);
            }

            $kelas = collect($siswaArray->kelas)->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'nama'              => $item->nama,
                    'tahun_ajaran'      => $item->tahun_ajaran,
                    'unit_sekolah'      => $item->unitSekolah?->nama,
                    'unit_sekolah_id'   => $item->unitSekolah?->id,
                    'wali'              => $item->wali?->name,
                    'wali_id'           => $item->wali?->id,
                    'active'            => $item->pivot->active
                ];
            })->toArray();
            return response()->json($kelas);
        } else {
            return response()->json(['message' => 'kelas siswa not found'], 404);
        }
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
        //
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
