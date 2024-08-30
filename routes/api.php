<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AdminAuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AdminAuthController::class, 'me'])->middleware('auth:api')->name('me');
});
