<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OptionsController;
use App\Http\Controllers\FormOptionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserMetaController;
use App\Http\Controllers\UserAlamatController;
use App\Http\Controllers\UnitSekolahController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UnitSekolahPegawaiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaKelasController;

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
        'users'                 => UsersController::class,
        'unitsekolah'           => UnitSekolahController::class,
        'pegawai'               => PegawaiController::class,
        'unitsekolahpegawai'    => UnitSekolahPegawaiController::class,
        'setting'               => SettingController::class,
        'kelas'                 => KelasController::class,
        'siswa'                 => SiswaController::class,
        'tahunajaran'           => TahunAjaranController::class,
        'siswakelas'            => SiswaKelasController::class
    ]);

    Route::match(['get', 'post'], 'pegawai/akun/{id}', [PegawaiController::class, 'akun'])->name('pegawai.akun');

    Route::get('usermeta/{user_id}', [UserMetaController::class, 'gets']);
    Route::post('usermeta/{user_id}', [UserMetaController::class, 'saves']);
    Route::get('useralamat/{user_id}', [UserAlamatController::class, 'get']);
    Route::post('useralamat/{user_id}', [UserAlamatController::class, 'update']);

    Route::match(['get', 'post'], 'setting_logo_lembaga', [SettingController::class, 'logo_lembaga'])->name('setting.logo_lembaga');

    Route::get('unitsekolah/pegawai/{id}', [UnitSekolahController::class, 'pegawais']);

    Route::get('form-options/unitsekolah', [FormOptionsController::class, 'option_unitsekolah']);
    Route::get('form-options/option-add-jabatan', [FormOptionsController::class, 'option_add_jabatan']);
    Route::get('form-options/option-add-kelas', [FormOptionsController::class, 'option_add_kelas']);

    Route::get('option/{name}', [OptionsController::class, 'get']);
});
