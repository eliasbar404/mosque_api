<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'events'
], function ($router) {

    Route::get('/'       ,[EventController::class, 'get_all_events'])->name('getAllEvents');
    Route::get('/{id}'   ,[EventController::class, 'get_event_by_id'])->name('getEvent');
    Route::post('/'      ,[EventController::class, 'create_event'])->middleware('auth:admin')->name('createEvent');
    Route::put('/{id}'   ,[EventController::class, 'update_event'])->middleware('auth:admin')->name('updateEvent');
    Route::delete('/{id}',[EventController::class, 'delete_event'])->middleware('auth:admin')->name('deleteEvent');

    Route::put('/{id}/publish',[EventController::class, 'publish_event'])->middleware('auth:admin')->name('publishEvent');
    Route::put('/{id}/view'   ,[EventController::class, 'event_view_count'])->name('viewhEvent');

    Route::post('/join',           [EventController::class, 'join_event'])->middleware('auth:member')->name('joinEvent');
    Route::put('/join/{id}/status',[EventController::class, 'join_event_status'])->middleware('auth:admin')->name('statusEvent');

});