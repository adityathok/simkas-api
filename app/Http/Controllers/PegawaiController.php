<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use App\Models\User;

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
        //validate
        $request->validate([
            'nip'               => 'required|min:3',
            'nama'              => 'required|min:4',
            'status'            => 'required|min:4',
            'tempat_lahir'      => 'required|min:4',
            'tanggal_lahir'     => 'required|min:4',
            'tanggal_masuk'     => 'required|min:4',
            'jenis_kelamin'     => 'required|min:4',
            'email'             => 'min:10',
        ]);

        $pegawai = new Pegawai();
        $pegawai->nip = $request->nip;
        $pegawai->nama = $request->nama;
        $pegawai->status = $request->status;
        $pegawai->tempat_lahir = $request->tempat_lahir;
        $pegawai->tanggal_lahir = $request->tanggal_lahir;
        $pegawai->tanggal_masuk = $request->tanggal_masuk;
        $pegawai->jenis_kelamin = $request->jenis_kelamin;
        $pegawai->nik = $request->nik;
        $pegawai->email = $request->email;
        $pegawai->save();

        return response()->json($pegawai);
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

        $request->validate([
            'nip'       => 'required|min:3',
            'nama'      => 'required|min:4',
            'status'    => 'required|min:4',
            'tempat_lahir'  => 'required|min:4',
            'tanggal_lahir'  => 'required|min:4',
            'tanggal_masuk'  => 'required|min:4',
            'jenis_kelamin'  => 'required|min:4',
            'email'  => 'min:10',
        ]);

        $pegawai = Pegawai::find($id);

        //update
        $pegawai->update([
            'nip'   => $request->nip,
            'nama'  => $request->nama,
            'status' => $request->status,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_masuk' => $request->tanggal_masuk,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
        ]);

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

    public function akun(Request $request, string $id)
    {
        $pegawai = Pegawai::select('id', 'nama', 'email', 'user_id')->with(['user'])->findOrFail($id);

        // Validasi input jika diperlukan (untuk POST)
        if ($request->isMethod('post')) {
            $request->validate([
                'name'      => 'required|min:3',
                'email'     => 'required|min:10',
                'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            if ($pegawai->user_id == null) {

                // Tambah data ke User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'type' => 'pegawai',
                    'password' => Hash::make($request->string('password')),
                ]);
                $user_id = $user->id;

                // Tambah data ke Pegawai
                $pegawai->update([
                    'user_id' => $user_id
                ]);
            } else {

                $user = User::find($pegawai->user_id);
                //update
                $user->update([
                    'password' => Hash::make($request->string('password')),
                ]);
            }

            $pegawai = Pegawai::select('id', 'nama', 'email', 'user_id')->with(['user'])->findOrFail($id);
            return response()->json($pegawai);
        }

        // Handle GET: Ambil data pegawai
        if ($request->isMethod('get')) {
            return response()->json($pegawai);
        }
    }
}
