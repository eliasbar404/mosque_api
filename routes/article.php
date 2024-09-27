<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'articles'
], function ($router) {

    Route::get('/'      ,[ArticleController::class, 'Get_Articles'])->name('getAllArticle');
    Route::get('/{id}'  ,[ArticleController::class, 'Get_Article'])->name('getArticle');


    Route::post('/'      ,[ArticleController::class, 'Create_Article'])->middleware('auth:admin,subadmin')->name('createArticle');
    Route::post('/{id}/update'   ,[ArticleController::class, 'Update_Article'])->middleware('auth:admin,subadmin')->name('updateArticle');
    Route::delete('/{id}',[ArticleController::class, 'Delete_Article'])->middleware('auth:admi,subadminn')->name('deleteArticle');

    
    Route::patch('/{id}/publish',[ArticleController::class, 'publish_article'])->middleware('auth:admin,subadmin')->name('publishArticle'); 
    Route::put('/{id}/view'   ,[ArticleController::class, 'increace_view_count'])->name('viewArticle');



    Route::post('/like'   ,[ArticleController::class, 'Like_Article'])->middleware('auth:member')->name('likeArticle'); 
    Route::post('/comment',[ArticleController::class, 'Comment_Article'])->middleware('auth:member')->name('commentArticle'); 

});