<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageUsersController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {


    Route::get('/subadmins' ,[ManageUsersController::class, 'get_All_subAdmins'])->middleware('auth:admin')->name('getAllSubAdmins');
    Route::get('/members'   ,[ManageUsersController::class, 'get_all_members'])->middleware('auth:admin,subadmin')->name('getAllMembers');
    Route::get('/subadmins/{id}' ,[ManageUsersController::class, 'get_subadmin'])->middleware('auth:admin,subadmin')->name('getSubAdminByID');
    Route::get('/members/{id}'   ,[ManageUsersController::class, 'get_member'])->middleware('auth:admin,subadmin')->name('getMemberByID');


    Route::patch('/subadmins/{id}/unactive' ,[ManageUsersController::class, 'subAdmin_unactive'])->middleware('auth:admin,subadmin')->name('changeSubAdminStatus');
    Route::patch('/subadmins/{id}/active'   ,[ManageUsersController::class, 'subAdmin_active'  ])->middleware('auth:admin,subadmin')->name('changeSubAdminStatus');

    Route::patch('/members/{id}/unactive'     ,[ManageUsersController::class, 'member_unactive'    ])->middleware('auth:admin,subadmin')->name('changeMemberStatus');
    Route::patch('/members/{id}/active'       ,[ManageUsersController::class, 'member_active'    ])->middleware('auth:admin,subadmin')->name('changeMemberStatus');

    Route::delete('/subadmins/{id}' ,[ManageUsersController::class, 'delete_subAdmin'])->middleware('auth:admin')->name('deleteSubAdmin');
    Route::delete('/members/{id}'   ,[ManageUsersController::class, 'delete_member'])->middleware('auth:admin,subadmin')->name('deleteMember');


});