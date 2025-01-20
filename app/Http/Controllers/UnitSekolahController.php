<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitSekolah;
use App\Http\Requests\UnitSekolahRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\FileUploadMan;
use Illuminate\Support\Str;

class UnitSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitSekolah = UnitSekolah::paginate(20);

        // Tambahkan data kepala sekolah ke setiap instance UnitSekolah 
        $unitSekolah->getCollection()->transform(function ($sekolah) {
            $sekolah->kepala_sekolah = $sekolah->kepalaSekolah();
            return $sekolah;
        });

        $unitSekolah->withPath('/unitsekolah');
        return response()->json($unitSekolah);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validated();

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
        // Ambil data sekolah berdasarkan ID 
        $sekolah = UnitSekolah::findOrFail($id);

        // Ambil informasi pegawai dengan jabatan KepalaSekolah
        $kepalaSekolah = $sekolah->kepalaSekolah();
        $sekolah->kepala_sekolah = $kepalaSekolah;

        // Ambil semua pegawai yang terkait dengan sekolah tersebut 
        $semuaPegawai = $sekolah->semuaPegawai();
        $sekolah->pegawais = $semuaPegawai;

        return response()->json($sekolah);
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
        if ($request->hasFile('file_logo')) {

            //hapus gambar sebelumnya
            if ($unitSekolah->logo) {
                FileUploadMan::findOrFail($unitSekolah->logo)->delete();
            }

            $file = FileUploadMan::saveFile($request->file('file_logo'), 'unitsekolah', auth()->user()->id);
            $validated['logo'] = $file->id;
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

    //daftar pegawai di unit sekolah
    public function pegawais(string $id)
    {
        $unitSekolah = UnitSekolah::findOrFail($id);
        return response()->json($unitSekolah->semuaPegawai());
    }
}
