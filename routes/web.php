<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadManController;

Route::get('/', function () {
    return [
        'app_name'  => "Aplikasi Sekolah",
        "version"   => "1.0.0",
        "status"    => "200",
        'laravel'   => app()->version(),
    ];
});

require __DIR__ . '/auth.php';


Route::middleware('auth:sanctum')->get('filestream/{guide}', [FileUploadManController::class, 'stream']);
