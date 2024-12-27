<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Pegawai = Pegawai::paginate(20);
        $Pegawai->withPath('/pegawai');
        return response()->json($Pegawai);
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
        $pegawai = Pegawai::with([
            'meta' => function ($query) {
                $query->select('key', 'value');
            },
            'alamat'
        ])->findOrFail($id);
        return response()->json($pegawai);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $pegawai = Pegawai::find($id);
        $pegawai->update($request->except(['foto']));

        //upload gambar
        if ($request['foto'] && $request->file('foto')) {
            // hapus gambar sebelumnya
            $oldimg = $pegawai->foto;
            if ($oldimg && Storage::disk('public')->exists($oldimg)) {
                Storage::disk('public')->delete($oldimg);
            }
            //upload di folder pegawai
            $foto_path = $request->file('foto')->store('pegawai/' . date('Y/m'), 'public');

            //update
            $pegawai->update([
                'foto' => $foto_path
            ]);
        }

        $response = [
            'success' => true,
            'foto'    => $pegawai->foto
        ];

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pegawai    = Pegawai::find($id);
        $nama       = $pegawai->nama;

        //hapus foto sebelumnya
        $oldimg = $pegawai->foto;
        if ($oldimg && Storage::disk('public')->exists($oldimg)) {
            Storage::disk('public')->delete($oldimg);
        }
        //hapus data pegawai
        $pegawai->delete();

        return response()->json([
            'message' => 'Pegawai ' . $nama . ' berhasil dihapus'
        ]);
    }
}
