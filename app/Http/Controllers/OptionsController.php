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

    public function get(request $request, string $name)
    {
        $result = [];

        switch ($name) {
            case 'unitsekolah':
                return $this->unitsekolah();
                break;
            case 'kelas':
                return $this->kelas($request);
                break;
            case 'tahun_ajaran':
                return $this->tahun_ajaran();
                break;
            case 'add_kelas':
                return $this->add_kelas();
                break;
            case 'siswakelas':
                return $this->siswakelas();
                break;
            default:
                return Setting::get($name);
        }

        return response()->json($result);
    }

    public function gets(request $request)
    {
        $keys = $request->query('keys');
        $keys = $keys ? explode(',', $request->query('keys', '')) : '';

        $result = [];
        if ($keys):
            foreach ($keys as $key) {
                $result[$key] = $this->get_option($key);
            }
        endif;

        return response()->json($result);
    }

    private function get_option($key, $request = null)
    {

        switch ($key) {
            case 'unitsekolah':
                return $this->unitsekolah();
                break;
            case 'kelas':
                return $this->kelas($request);
                break;
            case 'tahun_ajaran':
                return $this->tahun_ajaran();
                break;
            case 'add_kelas':
                return $this->add_kelas();
                break;
            case 'siswakelas':
                return $this->siswakelas();
                break;
            default:
                return Setting::get($key);
        }
    }

    public function tahun_ajaran()
    {
        $tahun_ajaran = TahunAjaran::all();
        ///collection
        $tahun_ajaran = collect($tahun_ajaran)->map(function ($item) {
            return [
                'value' => $item->nama,
                'label' => $item->nama,
                'active' => $item->active
            ];
        });

        return $tahun_ajaran;
    }

    public function kelas($request = null)
    {
        $tahun_ajaran = $request->tahun_ajaran ?? TahunAjaran::getActive()->nama;
        $unit = $request->unit_sekolah ?? null;

        // $kelas = Kelas::where('unit_sekolah_id', $unit)
        //     ->where('tahun_ajaran', $tahun_ajaran)
        //     ->orderBy('nama', 'asc')
        //     ->get();

        $kelas = Kelas::where('tahun_ajaran', $tahun_ajaran)
            ->when($unit, function ($query) use ($unit) {
                return $query->where('unit_sekolah_id', $unit);
            })
            ->orderBy('nama', 'asc')
            ->get();

        ///collection
        $kelas = collect($kelas)->map(function ($data) {
            return [
                'value'         => $data->id,
                'label'         => $data->nama . ' - ' . $data->tahun_ajaran,
                'tahun_ajaran'  => $data->tahun_ajaran
            ];
        });

        return $kelas;
    }

    private function unitsekolah()
    {
        $result = Cache::remember('option-unitsekolah', $this->time_cache, function () {
            $unit = UnitSekolah::all();
            //ringkas hasil
            $unit->transform(function ($data) {
                return [
                    'value'         => $data->id,
                    'label'         => $data->nama,
                ];
            });
            return $unit;
        });

        return $result;
    }

    private function add_kelas()
    {
        // $result = Cache::remember('option-addkelas', $this->time_cache, function () {
        $pegawai = Pegawai::with(['user:id,name,avatar', 'user.avatarFile:id,guide,path'])->get(); //ringkas array
        $pegawai->transform(function ($data) {
            return [
                'value'         => $data->user_id,
                'label'         => $data->user->pegawai->nama,
                'id'            => $data->id,
                'user_id'       => $data->user_id,
                'pegawai_id'    => $data->user->pegawai->id,
                'nama'          => $data->user->pegawai->nama,
                'avatar'        => $data->user->avatar_url,
                'nip'           => $data->user->pegawai->nip
            ];
        });


        $unit = UnitSekolah::all();
        $unit->transform(function ($data) {
            return [
                'value'     => $data->id,
                'label'     => $data->nama,
                'id'        => $data->id,
                'nama'      => $data->nama,
                'tingkat'   => $data->tingkat ?? [],
                'rombel'    => $data->rombel ?? []
            ];
        });

        $tahun_ajarans = TahunAjaran::all();
        $tahun_ajarans->transform(function ($data) {
            return [
                'value' => $data->nama,
                'label' => $data->nama,
                'active' => $data->active
            ];
        });

        return [
            'tahun_ajaran'  => TahunAjaran::getActive()->nama,
            'tahun_ajarans' => $tahun_ajarans,
            'unit_sekolah'  => $unit,
            'pegawai'       => $pegawai
        ];
        // });

        return $result;
    }
}
