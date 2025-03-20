<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalKas;

class JurnalKasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurnalKas = JurnalKas::orderBy('nama', 'asc')->paginate(20);
        $jurnalKas->withPath('/jurnalKas');

        return response()->json($jurnalKas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'kas'   => 'required',
            'neraca' => 'required|boolean',
            'jurnal_khusus' => 'required|boolean',
            'likuiditas' => 'required|boolean',
        ]);

        $jurnalkas = JurnalKas::create([
            'nama'  => $request->nama,
            'kas'   => $request->kas,
            'neraca' => $request->neraca,
            'jurnal_khusus' => $request->jurnal_khusus,
            'likuiditas' => $request->likuiditas
        ]);

        return response()->json($jurnalkas);
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
            'nama'  => 'required',
            'kas'   => 'required',
            'neraca' => 'required|boolean',
            'jurnal_khusus' => 'required|boolean',
            'likuiditas' => 'required|boolean',
        ]);

        //temukan jurnal kas
        $jurnalKas = JurnalKas::find($id);

        $jurnalKas->update([
            'nama'  => $request->nama,
            'kas'   => $request->kas,
            'neraca' => $request->neraca,
            'jurnal_khusus' => $request->jurnal_khusus,
            'likuiditas' => $request->likuiditas
        ]);

        return response()->json($jurnalKas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //temukan jurnal kas
        $jurnalKas = JurnalKas::find($id);

        $jurnalKas->delete();
    }
}
