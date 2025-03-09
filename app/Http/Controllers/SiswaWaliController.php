<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SiswaWali;

class SiswaWaliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //request GET
        $siswa_id = $request->input('siswa_id');

        $wali = SiswaWali::where('siswa_id', $siswa_id)
            ->get();

        return response()->json($wali);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'      => 'required|min:6',
            'nama'          => 'required|min:4',
            'hubungan'      => 'required|min:3',
            'tahun_lahir'   => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan'    => 'nullable',
            'pekerjaan'     => 'nullable',
            'penghasilan'   => 'nullable',
            'telepon'       => 'nullable',
            'email'         => 'nullable|email',
            'alamat'        => 'nullable',
        ]);

        $wali = SiswaWali::create([
            'siswa_id'      => $request->siswa_id,
            'nama'          => $request->nama,
            'hubungan'      => $request->hubungan,
            'tahun_lahir'   => $request->tahun_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pendidikan'    => $request->pendidikan,
            'pekerjaan'     => $request->pekerjaan,
            'penghasilan'   => $request->penghasilan,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
        ]);

        return response()->json($wali);
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
        $wali = SiswaWali::find($id);
        $wali->delete();
    }
}
