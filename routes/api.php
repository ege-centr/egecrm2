<?php

use Illuminate\Http\Request;
use App\Models\Factory\{Branch, Subject, Grade, Year};
use App\Models\Admin\Admin;
use App\Http\Resources\Admin\Collection as AdminCollection;

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
    Route::get('logout', 'LoginController@logout');

    Route::prefix('data')->group(function() {
        Route::post('enum', 'DataController@enum');
        Route::post('static', 'DataController@static');
    });


    Route::apiResources([
        'admins' => 'AdminsController',
        'requests' => 'RequestsController',
        'clients' => 'ClientsController',
        'groups' => 'GroupsController',
        'group-clients' => 'GroupClientsController',
        'comments' => 'CommentsController',
        'teachers' => 'TeachersController',
        'cabinets' => 'CabinetsController',
        'tasks' => 'TasksController',
        'logs' => 'LogsController',
        'payments' => 'PaymentsController',
        'contracts' => 'ContractsController',
        'special-dates' => 'SpecialDatesController',
        'lessons' => 'LessonsController',
    ]);

    Route::resource('settings', 'SettingsController')->only(['index', 'store']);

    Route::prefix('sms')->group(function() {
        Route::get('/', 'SmsController@index');
        Route::post('send', 'SmsController@send');
    });

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
            'years' => Year::all(),
            'admins' => AdminCollection::collection(Admin::all())
        ]);
    });

    Route::get('rights', function() {
        return response()->json([
            'all' => \Shared\Rights::$all,
            'groups' => \Shared\Rights::$groups
        ]);
    });
});
