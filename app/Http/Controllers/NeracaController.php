<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunPendapatan;
use App\Models\AkunPengeluaran;

class NeracaController extends Controller
{
    public function akun(Request $request)
    {
        //get akun pendapatan dengan neraca = true
        $akun_pendapatan = AkunPendapatan::where('neraca', true)->get();
        $akun_pengeluaran = AkunPengeluaran::all();
        return response()->json([
            'akun_pendapatan' => $akun_pendapatan,
            'akun_pengeluaran' => $akun_pengeluaran,
        ]);
    }
}
