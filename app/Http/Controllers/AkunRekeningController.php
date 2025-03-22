<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunRekening;

class AkunRekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $akun = AkunRekening::orderBy('nama', 'asc')
            ->paginate(20);
        $akun->withPath('/akunrekening');

        return response()->json($akun);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3',
            'keterangan' => 'nullable',
        ]);

        $akun = AkunRekening::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json($akun);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //temukan akun rekening
        $akun = AkunRekening::find($id);

        return response()->json($akun);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //jika id == 'CASH'
        if ($id == 'CASH') {
            return response()->json([
                'message' => 'Tidak dapat mengubah akun rekening',
            ]);
        }

        $request->validate([
            'nama' => 'required|min:3',
            'keterangan' => 'nullable',
        ]);

        //temukan akun rekening
        $akun = AkunRekening::find($id);

        //update akun rekening
        $akun->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json($akun);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //jika id == 'CASH'
        if ($id == 'CASH') {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun rekening',
            ]);
        }
        //temukan akun rekening
        $akun = AkunRekening::find($id);

        $akun->delete();
    }
}
