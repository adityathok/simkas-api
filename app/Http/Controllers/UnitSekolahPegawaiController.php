<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitSekolahPegawai;

class UnitSekolahPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $unit_sekolah_id = $request->input('unit_sekolah_id');

        $pegawaiList = UnitSekolahPegawai::where('unit_sekolah_id', $unit_sekolah_id)->with('user.pegawai')->paginate(50);
        //ringkas hasil
        $pegawaiList->getCollection()->transform(function ($data) {
            return [
                'id'            => $data->id,
                'user_id'       => $data->user_id,
                'pegawai_id'    => $data->user->pegawai->id,
                'nama'          => $data->user->pegawai->nama,
                'jabatan'       => $data->jabatan,
                'avatar'        => $data->user->avatar
            ];
        });

        return response()->json($pegawaiList);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_sekolah_id' => 'required|min:3',
            'user_id' => 'required|min:3',
            'jabatan' => 'required|min:3',
        ]);

        $pegawai = UnitSekolahPegawai::create([
            'unit_sekolah_id' => $request->unit_sekolah_id,
            'user_id' => $request->user_id,
            'jabatan' => $request->jabatan,
        ]);

        return response()->json($pegawai);
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
            'jabatan' => 'required|min:3',
        ]);

        $pegawai = UnitSekolahPegawai::find($id);
        $pegawai->update([
            'jabatan' => $request->jabatan,
        ]);
        return response()->json($pegawai);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = UnitSekolahPegawai::find($id);
        $jabatan->delete();
    }
}
