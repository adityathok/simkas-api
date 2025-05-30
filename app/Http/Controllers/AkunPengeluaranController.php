<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunPengeluaran;

class AkunPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $akun = AkunPengeluaran::with('akun_pendapatan')
            ->orderBy('nama', 'asc')
            ->paginate(20);
        $akun->withPath('/akunpengeluaran');

        return response()->json($akun);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'akunpendapatan_id' => 'required',
            'nama' => 'required',
        ]);

        //dapatkan id user yang sedang login
        $user = auth()->user();
        $user_id = $user ? $user->id : null;

        //sumber
        $sumber = 'jurnalkas';
        $pendapatan_id = $request->akunpendapatan_id;
        if ($pendapatan_id == 'INKASNERACA') {
            $sumber = 'kasneraca';
        }

        $akun = AkunPengeluaran::create([
            'pendapatan_id'     => $request->akunpendapatan_id,
            'sumber'            => $sumber,
            'nama'              => $request->nama,
            'admin_id'          => $user_id
        ]);

        return response()->json($akun);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //temukan akun pengeluaran
        $akunpengeluaran = AkunPengeluaran::with('akun_pendapatan')
            ->find($id);

        return response()->json($akunpengeluaran);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'akunpendapatan_id' => 'required',
            'nama' => 'required',
        ]);
        //sumber
        $sumber = 'jurnalkas';
        $pendapatan_id = $request->akunpendapatan_id;
        if ($pendapatan_id == 'INKASNERACA') {
            $sumber = 'kasneraca';
        }

        //temukan akun pengeluaran
        $akun = AkunPengeluaran::find($id);
        //update
        $akun->update([
            'pendapatan_id'     => $request->akunpendapatan_id,
            'sumber'            => $sumber,
            'nama'              => $request->nama,
        ]);

        return response()->json($akun);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //temukan akun pengeleuaran
        $akunpengeluaran = AkunPengeluaran::with('akunpendapatan')
            ->find($id);

        $akunpengeluaran->delete();
    }
}
