<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitSekolah;
use App\Http\Requests\UnitSekolahRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UnitSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitSekolah = UnitSekolah::paginate(20);
        $unitSekolah->withPath('/unitsekolah');
        return response()->json($unitSekolah);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|in:TK,KB,SD,SMP',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'nullable|email',
            'kode_pos' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'tanggal_dibentuk' => 'nullable|date',
            'kepala_sekolah_id' => 'nullable|string',
            'jumlah_siswa' => 'nullable|integer',
        ]);

        $unitSekolah = UnitSekolah::create([
            'id' => \Illuminate\Support\Str::ulid(), // Menggunakan ULID
            'nama' => $request->nama,
            'jenjang' => $request->jenjang,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'kode_pos' => $request->kode_pos,
            'status' => $request->status,
            'tanggal_dibentuk' => $request->tanggal_dibentuk,
            'kepala_sekolah_id' => $request->kepala_sekolah_id,
            'jumlah_siswa' => $request->jumlah_siswa,
        ]);

        return response()->json($unitSekolah, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unitSekolah = UnitSekolah::find($id);

        if (!$unitSekolah) {
            return response()->json(['message' => 'Unit sekolah tidak ditemukan'], 404);
        }

        return response()->json($unitSekolah);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitSekolahRequest $request, string $id)
    {
        // Validasi
        $validated = $request->validated();

        $unitSekolah = UnitSekolah::find($id);

        if (!$unitSekolah) {
            return response()->json(['message' => 'Unit sekolah tidak ditemukan'], 404);
        }

        // Cek jika ada file gambar yang diunggah
        if ($request->hasFile('logo')) {

            // hapus gambar sebelumnya
            $oldimg = $unitSekolah->logo;
            if ($oldimg && Storage::disk('public')->exists($oldimg)) {
                Storage::disk('public')->delete($oldimg);
            }

            // Simpan file baru
            $path = $request->file('logo')->store('unitsekolah', 'public');
            $validated['logo'] = $path; // Tambahkan path ke data yang akan diupdate
        }

        $unitSekolah->update($validated);

        return response()->json($unitSekolah);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unitSekolah = UnitSekolah::find($id);

        if (!$unitSekolah) {
            return response()->json(['message' => 'Unit sekolah tidak ditemukan'], 404);
        }

        $unitSekolah->delete();
        return response()->json(['message' => 'Unit sekolah berhasil dihapus']);
    }
}
