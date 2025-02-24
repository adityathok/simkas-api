<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitSekolah;
use App\Models\Kelas;

class UnitSekolahKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        //get kelas dari id unit
        $kelas = Kelas::where('unit_sekolah_id', $id)
            ->with('wali:id,name', 'wali.pegawai:id,nama,user_id')
            ->orderBy('nama', 'asc')
            ->paginate(20);

        return response()->json($kelas);
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
