<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel'   => app()->version(),
        'app_name'  => 'Sistem Administrasi Keuangan Sekolah',
        'date'      => date("d-m-y H:i:s"),
        'version'   => '0.0.1',
    ];
});

require __DIR__ . '/auth.php';
