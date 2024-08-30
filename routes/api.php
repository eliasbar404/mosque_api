<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\MemberAuthController;



// ------ Admin Auth Routes ------
// -------------------------------
Route::group([
    'middleware' => 'api',
    'prefix' => 'admin/auth'
], function ($router) {
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('logout');
    Route::post('/refresh', [AdminAuthController::class, 'refresh'])->middleware('auth:admin')->name('refresh');
    Route::post('/me', [AdminAuthController::class, 'me'])->middleware('auth:admin')->name('me');
});


// ------ Member Auth Routes ------
// --------------------------------

Route::group([
    'middleware' => 'api',
    'prefix' => 'member/auth'
], function ($router) {
    Route::post('/register', [MemberAuthController::class, 'register'])->name('register');
    Route::post('/login', [MemberAuthController::class, 'login'])->name('login');
    Route::post('/logout', [MemberAuthController::class, 'logout'])->middleware('auth:member')->name('logout');
    Route::post('/refresh', [MemberAuthController::class, 'refresh'])->middleware('auth:member')->name('refresh');
    Route::post('/me', [MemberAuthController::class, 'me'])->middleware('auth:member')->name('me');
});
