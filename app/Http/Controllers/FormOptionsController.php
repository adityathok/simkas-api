<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\UnitSekolah;
use App\Models\Pegawai;

class FormOptionsController extends Controller
{
    //
    public function option_add_jabatan()
    {
        $pegawai = Pegawai::with(['user:id,name,avatar', 'user.avatarFile:id,guide,path'])->get();
        //ringkas array
        $pegawai->transform(function ($data) {
            return [
                'id'            => $data->id,
                'user_id'       => $data->user_id,
                'pegawai_id'    => $data->user->pegawai->id,
                'nama'          => $data->user->pegawai->nama,
                'avatar'        => $data->user->avatar_url,
                'nip'           => $data->user->pegawai->nip
            ];
        });
        return response()->json([
            'pegawai' => $pegawai,
            'jabatan' => Setting::get('jabatan')
        ]);
    }
}
