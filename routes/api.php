<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\SubAdminAuthController;


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
    Route::post('/update_profile/{id}',  [AdminAuthController::class, 'update_profile'])->middleware('auth:admin')->name('update_profile');

    // update password
    Route::post('/update_password/{id}', [AdminAuthController::class, 'update_password'])->middleware('auth:admin')->name('update_password');
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


//-------- Sub Admin Auth
// -----------------------

Route::group([
    'middleware' => 'api',
    'prefix' => 'subadmin/auth'
], function ($router) {
    Route::post('/register', [SubAdminAuthController::class, 'register'])->middleware('auth:admin')->name('register');
    Route::post('/login', [SubAdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [SubAdminAuthController::class, 'logout'])->middleware('auth:subadmin')->name('logout');
    Route::post('/refresh', [SubAdminAuthController::class, 'refresh'])->middleware('auth:subadmin')->name('refresh');
    Route::post('/me', [SubAdminAuthController::class, 'me'])->middleware('auth:subadmin')->name('me');

    // update profile
    Route::post('/update_profile',  [SubAdminAuthController::class, 'update_profile'])->middleware('auth:subadmin')->name('update_profile');

    // update password
    Route::post('/update_password',  [SubAdminAuthController::class, 'update_password'])->middleware('auth:subadmin')->name('update_password');
});



// Contact Routes
require __DIR__.'/contact.php';
// Article Routes
require __DIR__.'/article.php';
// Event  Routes
require __DIR__.'/events.php';
// Manage Users Routes
require __DIR__.'/manageUsers.php';