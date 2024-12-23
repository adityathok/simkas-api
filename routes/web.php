<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'app_name'  => "Aplikasi Sekolah",
        "version"   => "1.0.0",
        "status"    => "200",
        'laravel'   => app()->version(),
    ];
});

require __DIR__ . '/auth.php';
