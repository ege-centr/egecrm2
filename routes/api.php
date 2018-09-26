<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api\v1')->prefix('v1')->group(function() {
    Route::post('login', 'LoginController@login');

    Route::prefix('data')->group(function() {
        Route::post('enum', 'DataController@enum');
        Route::post('static', 'DataController@static');
    });

    Route::apiResource('users', 'UsersController');
    Route::apiResource('requests', 'RequestsController');

});
