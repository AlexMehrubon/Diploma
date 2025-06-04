<?php

use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->group(function () {
    Route::get('/current', CurrentUserController::class);

});
Route::post('/v1/users/{user}', [UserController::class, 'update']);
Route::resource('v1/users', UserController::class);
Route::resource('v1/roles', RoleController::class);


