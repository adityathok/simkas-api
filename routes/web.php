<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadManController;
use App\Models\Setting;

Route::get('/', function () {
    //tampilkan copyright
    return 'Your IP Address: ' . $_SERVER['REMOTE_ADDR'] . '<br><br><small>Copyright © ' . date('Y') . ' ' . Setting::get('nama_lembaga', 'SIMKAS') . '</small>';
});
Route::get('/infoapp', function () {
    return [
        'app_name'  => Setting::get('app_name', 'Sistem Informasi Akademik Keuangan Sekolah'),
        'lembaga'   => Setting::get('nama_lembaga', 'SIMKAS'),
        'logo'      => Setting::get('logo_lembaga', ''),
        "version"   => "1.0.0",
        "status"    => "200"
    ];
});

require __DIR__ . '/auth.php';


Route::middleware('auth:sanctum')->get('filestream/{guide}', [FileUploadManController::class, 'stream']);
