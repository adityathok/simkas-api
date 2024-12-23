<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UnitSekolahController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('users/password/{id}', [UsersController::class, 'update_password']);

    Route::apiResources([
        'users' => UsersController::class,
        'unitsekolah' => UnitSekolahController::class,
        // 'posts' => PostController::class,
    ]);
});
