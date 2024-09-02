<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'contact'
], function ($router) {

    Route::post('/'      ,[ContactController::class, 'Create_Contact'])->name('createContact');

    Route::get('/'       ,[ContactController::class, 'Get_All_Contats'])->middleware('auth:admin')->name('getAllContacts');
    Route::get('/{id}'   ,[ContactController::class, 'Get_Contact'])->middleware('auth:admin')->name('getContact');
    Route::delete('/{id}',[ContactController::class, 'Delete_Contact'])->middleware('auth:admin')->name('deleteContact');
    Route::put('/{id}'   ,[ContactController::class, 'Change_Status'])->middleware('auth:admin')->name('changeStatus');

});
