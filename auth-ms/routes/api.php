<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function () {
    Route::get('user', function (Request $request) {
       return auth('api')->user();
    });
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);

    Route::resource('users', UserController::class);
});


