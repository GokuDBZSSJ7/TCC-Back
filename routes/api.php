<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('register', [UserController::class, 'store']);

Route::group(['middleware' => ['auth:sanctum', 'access_control']], function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('app/logout', [AuthController::class, 'logout'])->name('app.logout');
    Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
    Route::resource('users', UserController::class);
});

Route::post('auth/login', [AuthController::class, 'login']);