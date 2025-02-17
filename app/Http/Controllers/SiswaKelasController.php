<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
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

            // return response()->json($siswaArray);

            $kelas = collect($siswaArray->kelas)->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'nama'              => $item->nama,
                    'tahun_ajaran'      => $item->tahun_ajaran,
                    'unit_sekolah'      => $item->unitSekolah?->nama,
                    'unit_sekolah_id'   => $item->unitSekolah?->id,
                    'wali'              => $item->wali?->name,
                    'wali_id'           => $item->wali?->id,
                    'active'            => $item->pivot->active,
                    'kelas_id'          => $item->pivot->kelas_id
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
        $request->validate([
            'siswa_id'  => 'required',
            'user_id'   => 'required',
            'kelas_id'  => 'required',
            'active'    => 'required'
        ]);

        //temukan siswa
        $siswa = Siswa::find($request->siswa_id);

        if (!$siswa) {
            return response()->json(['message' => 'siswa not found'], 404);
        }

        //update kelas siswa
        $siswa->kelas()->attach($request->kelas_id, ['active' => $request->active]);

        return response()->json($siswa);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //dapatkan list siswa dari id kelas
        $siswa = Siswa::with('kelas', 'kelas.unitSekolah', 'kelas.wali')->whereHas('kelas', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();

        return response()->json($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'siswa_id'  => 'required',
            'user_id'   => 'required',
            'kelas_id'  => 'required',
            'active'    => 'required'
        ]);

        //temukan siswa
        $siswa = Siswa::find($request->siswa_id);

        if (!$siswa) {
            return response()->json(['message' => 'siswa not found'], 404);
        }

        //hapus pivot
        $siswa->kelas()->detach($request->kelas_old);

        //update pivot kelas siswa
        $siswa->kelas()->attach($request->kelas_id, ['active' => $request->active]);

        return response()->json($siswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //
        $request->validate([
            'siswa'  => 'required',
        ]);

        $siswa = Siswa::find($request->siswa);

        if (!$siswa) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        //hapus pivot kelas siswa
        $siswa->kelas()->detach($id);

        return response()->json(['message' => 'Kelas Siswa deleted'], 200);
    }
}
