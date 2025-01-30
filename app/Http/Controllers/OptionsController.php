<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UnitSekolah;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class OptionsController extends Controller
{
    //
    public function get(string $name)
    {

        $result = [];

        switch ($name) {
            case 'unitsekolah':
                $result = $this->unitsekolah();
                break;
            case 'kelas':
                $result = Kelas::all();
                break;
            case 'tahunajaran':
                $result = TahunAjaran::all();
                break;
            default:
                $result = [];
        }

        return $result;

        return response()->json($data);
    }

    private function unitsekolah()
    {
        $unit = UnitSekolah::all();
        //ringkas hasil
        $unit->transform(function ($data) {
            return [
                'value' => $data->id,
                'label' => $data->nama
            ];
        });
        return $unit;
    }
}
