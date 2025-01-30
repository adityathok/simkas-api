<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dapatkan semua tahun ajaran
        $tahunAjaran = TahunAjaran::all();
        return response()->json($tahunAjaran);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'id'        => 'required',
            'nama'      => 'required',
            'mulai'     => 'required',
            'akhir'     => 'required',
            'active'    => 'required'
        ]);

        //simpan updateorcreate
        $tahunAjaran = TahunAjaran::updateOrCreate(
            ['id' => $request->id],
            [
                'nama' => $request->nama,
                'mulai' => $request->mulai,
                'akhir' => $request->akhir,
                'active' => $request->active
            ]
        );
        return response()->json($tahunAjaran);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tahunAjaran = TahunAjaran::find($id);
        return response()->json($tahunAjaran);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'nama'      => 'required',
            'mulai'     => 'required',
            'akhir'     => 'required',
            'active'    => 'required'
        ]);

        //simpan updateorcreate
        $tahunAjaran = TahunAjaran::updateOrCreate(
            [$id],
            [
                'nama' => $request->nama,
                'mulai' => $request->mulai,
                'akhir' => $request->akhir,
                'active' => $request->active
            ]
        );
        return response()->json($tahunAjaran);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tahunAjaran = TahunAjaran::find($id);
        $tahunAjaran->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
