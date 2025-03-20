<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunPendapatan;

class AkunPendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $akunpendapatan = AkunPendapatan::with('jurnalkas')
            ->orderBy('nama', 'asc')
            ->paginate(20);
        $akunpendapatan->withPath('/akunpendapatan');

        return response()->json($akunpendapatan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'jurnalkas_id'  => 'nullable',
            'neraca'        => 'required|boolean',
            'jurnal_khusus' => 'required|boolean',
        ]);

        //dapatkan id user yang sedang login
        $user = auth()->user();
        $user_id = $user ? $user->id : null;

        $akunpendapatan = AkunPendapatan::create([
            'nama'          => $request->nama,
            'jurnalkas_id'  => $request->jurnalkas_id,
            'neraca'        => $request->neraca,
            'jurnal_khusus' => $request->jurnal_khusus,
            'admin_id'      => $user_id
        ]);

        return response()->json($akunpendapatan);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //temukan akun pendapatan
        $akunpendapatan = AkunPendapatan::find($id);

        return response()->json($akunpendapatan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'          => 'required',
            'jurnalkas_id'  => 'nullable',
            'neraca'        => 'required|boolean',
            'jurnal_khusus' => 'required|boolean',
        ]);

        //temukan akun pendapatan
        $akunpendapatan = AkunPendapatan::find($id);

        //update akun pendapatan
        $akunpendapatan->update([
            'nama'          => $request->nama,
            'jurnalkas_id'  => $request->jurnalkas_id,
            'neraca'        => $request->neraca,
            'jurnal_khusus' => $request->jurnal_khusus,
        ]);

        return response()->json($akunpendapatan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //temukan akun pendapatan
        $akunpendapatan = AkunPendapatan::find($id);

        $akunpendapatan->delete();
    }
}
