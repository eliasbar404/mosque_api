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

    // update profile
    Route::post('/update_profile',  [AdminAuthController::class, 'update_profile'])->middleware('auth:admin')->name('update_profile');

    // update password
    Route::post('/update_password', [AdminAuthController::class, 'update_password'])->middleware('auth:admin')->name('update_password');
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

    // update profile
    Route::post('/update_profile',  [MemberAuthController::class, 'update_profile'])->middleware('auth:member')->name('update_profile');

    // update password
    Route::post('/update_password',  [MemberAuthController::class, 'update_password'])->middleware('auth:member')->name('update_password');
});



// Contact Routes
require __DIR__.'/contact.php';