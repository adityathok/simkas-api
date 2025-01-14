<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserMetaController;
use App\Http\Controllers\UserAlamatController;
use App\Http\Controllers\UnitSekolahController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SettingController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'show_user']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('users/password/{id}', [UsersController::class, 'update_password']);
    Route::get('user/avatar/{id}', [UsersController::class, 'get_avatar']);
    Route::post('user/avatar/{id}', [UsersController::class, 'update_avatar']);
    Route::delete('user/avatar/{id}', [UsersController::class, 'delete_avatar']);

    Route::apiResources([
        'users' => UsersController::class,
        'unitsekolah' => UnitSekolahController::class,
        'pegawai' => PegawaiController::class,
        'setting' => SettingController::class
    ]);

    Route::match(['get', 'post'], 'pegawai/akun/{id}', [PegawaiController::class, 'akun'])->name('pegawai.akun');

    Route::get('usermeta/{user_id}', [UserMetaController::class, 'gets']);
    Route::post('usermeta/{user_id}', [UserMetaController::class, 'saves']);
    Route::get('useralamat/{user_id}', [UserAlamatController::class, 'get']);
    Route::post('useralamat/{user_id}', [UserAlamatController::class, 'update']);

    Route::match(['get', 'post'], 'setting_logo_lembaga', [SettingController::class, 'logo_lembaga'])->name('setting.logo_lembaga');
});
