<?php

use Illuminate\Http\Request;
use App\Models\Factory\{Branch, Subject, Grade};
use App\Models\Admin;
use App\Http\Resources\Admin\Resource as AdminResource;

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

    Route::apiResource('admins', 'AdminsController');
    Route::apiResource('requests', 'RequestsController');
    Route::apiResource('clients', 'ClientsController');

    Route::prefix('photo')->group(function() {
        Route::post('upload', 'PhotosController@upload');
        Route::post('crop', 'PhotosController@crop');
    });

    # Load initial data
    Route::get('load-initial', function() {
        return response()->json([
            'branches' => Branch::all(),
            'subjects' => Subject::all(),
            'grades' => Grade::all(),
            'years' => array_map(function($year) {
                return ['value' => $year, 'text' => $year . '–' . ($year + 1) . ' уч. г.'];
            }, [2015, 2016, 2017, 2018]),
            'admins' => AdminResource::collection(Admin::all())
        ]);
    });

    Route::get('rights', function() {
        return response()->json([
            'all' => \Shared\Rights::$all,
            'groups' => \Shared\Rights::$groups
        ]);
    });
});
