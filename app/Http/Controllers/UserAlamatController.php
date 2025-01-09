<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAlamat;

class UserAlamatController extends Controller
{
    /**
     * Alamat User by ID User.
     */
    public function get(Request $request, string $user_id)
    {
        $alamat = UserAlamat::where('user_id', $user_id)->first();
        return response()->json($alamat);
    }

    public function update(Request $request, string $user_id)
    {
        $request->validate([
            'alamat'    => 'min:3',
            'rt'        => 'min:1',
            'rw'        => 'min:1',
            'dusun'     => 'min:1',
            'kelurahan' => 'min:3',
            'kecamatan' => 'min:3',
            'kota'      => 'min:3',
            'provinsi'  => 'min:3',
            'kode_pos'  => 'min:3',
            'jenis_tinggal' => 'min:3',
            'transportasi'  => 'min:8',
            'jarak'         => 'min:1',
        ]);

        $alamat = UserAlamat::where('user_id', $user_id)->first();
        $alamat->update($request->all());
        return response()->json($alamat);
    }
}
