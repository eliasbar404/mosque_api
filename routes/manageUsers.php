<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageUsersController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {


    Route::get('/subadmins' ,[ManageUsersController::class, 'get_All_subAdmins'])->middleware('auth:admin')->name('getAllSubAdmins');
    Route::get('/members'   ,[ManageUsersController::class, 'get_all_members'])->middleware('auth:admin')->name('getAllMembers');
    Route::get('/subadmins/{id}' ,[ManageUsersController::class, 'get_subadmin'])->middleware('auth:admin')->name('getSubAdminByID');
    Route::get('/members/{id}'   ,[ManageUsersController::class, 'get_member'])->middleware('auth:admin')->name('getMemberByID');


    Route::put('/subadmins/{id}/status' ,[ManageUsersController::class, 'subAdmin_status'])->middleware('auth:admin')->name('changeSubAdminStatus');
    Route::put('/members/{id}/status'   ,[ManageUsersController::class, 'member_status'])->middleware('auth:admin')->name('changeMemberStatus');


    Route::delete('/subadmins/{id}' ,[ManageUsersController::class, 'delete_subAdmin'])->middleware('auth:admin')->name('deleteSubAdmin');
    Route::delete('/members/{id}'   ,[ManageUsersController::class, 'delete_member'])->middleware('auth:admin')->name('deleteMember');


});