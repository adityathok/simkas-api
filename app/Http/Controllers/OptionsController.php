<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UnitSekolah;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Setting;
use App\Models\Pegawai;

class OptionsController extends Controller
{
    //time cache
    public $time_cache = (60 * 5);

    public function get(string $name)
    {
        $result = [];

        switch ($name) {
            case 'unitsekolah':
                return $this->unitsekolah();
                break;
            case 'kelas':
                $result = Kelas::all();
                break;
            case 'tahunajaran':
                $result = TahunAjaran::all();
                break;
            case 'add_kelas':
                return $this->add_kelas();
                break;
            default:
                $result = Cache::remember('option-' . $name, $this->time_cache, function ($name) {
                    return Setting::get($name);
                });
        }

        return response()->json($result);
    }

    private function unitsekolah()
    {
        $result = Cache::remember('option-unitsekolah', $this->time_cache, function () {
            $unit = UnitSekolah::all();
            //ringkas hasil
            $unit->transform(function ($data) {
                return [
                    'value' => $data->id,
                    'label' => $data->nama
                ];
            });
            return $unit;
        });

        return $result;
    }

    private function add_kelas()
    {
        // $result = Cache::remember('option-addkelas', $this->time_cache, function () {
        $unit = UnitSekolah::all();
        $pegawai = Pegawai::with(['user:id,name,avatar', 'user.avatarFile:id,guide,path'])->get(); //ringkas array
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
        return [
            'tahun_ajaran'  => TahunAjaran::getActive(),
            'unit_sekolah'  => $unit,
            'pegawai'       => $pegawai
        ];
        // });

        return $result;
    }
}
