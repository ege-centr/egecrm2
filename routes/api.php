<?php

use Illuminate\Http\Request;
use App\Models\Data\{Branch, Subject, Grade};
use App\Models\User;
use App\Http\Resources\User\Resource as UserResource;

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
    Route::apiResource('students', 'StudentsController');

    # Load initial data
    Route::get('load-initial', function() {
        return response()->json([
            'branches' => Branch::all(),
            'subjects' => Subject::all(),
            'grades' => Grade::all(),
            'years' => array_map(function($year) {
                return ['value' => $year, 'text' => $year . '–' . ($year + 1) . ' уч. г.'];
            }, [2015, 2016, 2017, 2018]),
            'users' => UserResource::collection(User::all())
        ]);
    });
});
