<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_pendapatan' => 25000000,
            'total_pengeluaran' => 350000000,
            'total_rekening' => 4500000000,
            'total_neraca' => 350000000,
        ]);
    }
}
