<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TahunAjaran;

class ConfigAppController extends Controller
{
    public function index()
    {
        $response = [
            'app_name'      => Setting::get('app_name', 'Sistem Informasi Akademik Keuangan Sekolah'),
            'lembaga'       => Setting::get('nama_lembaga', 'SIMKAS'),
            'logo'          => Setting::get('logo_lembaga', ''),
            'tahun_ajaran'  => TahunAjaran::getActive()->nama,
            "version"       => "1.0.0",
            "year"          => date('Y'),
            "date"          => date('Y/m/d'),
        ];

        return response()->json($response);
    }
}
