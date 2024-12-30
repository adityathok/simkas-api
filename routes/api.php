<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UnitSekolahController;
use App\Http\Controllers\PegawaiController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'show_user']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('users/password/{id}', [UsersController::class, 'update_password']);
    Route::post('users/updateavatar/{id}', [UsersController::class, 'update_avatar']);

    Route::apiResources([
        'users' => UsersController::class,
        'unitsekolah' => UnitSekolahController::class,
        'pegawai' => PegawaiController::class,
        // 'posts' => PostController::class,
    ]);

    Route::match(['get', 'post'], 'pegawai/akun/{id}', [PegawaiController::class, 'akun'])->name('pegawai.akun');
});
