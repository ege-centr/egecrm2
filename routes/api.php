<?php

use Illuminate\Http\Request;

Route::namespace('Api\v1')->prefix('v1')->group(function() {

    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');

    Route::get('initial-data', 'InitialDataController@index');

    Route::delete('group-clients', 'GroupClientsController@destroy');
    Route::post('group-clients', 'GroupClientsController@store');

    Route::apiResources([
        'admins' => 'AdminsController',
        'requests' => 'RequestsController',
        'clients' => 'ClientsController',
        'groups' => 'GroupsController',
        'group-acts' => 'GroupActsController',
        'comments' => 'CommentsController',
        'teachers' => 'TeachersController',
        'cabinets' => 'CabinetsController',
        'tasks' => 'TasksController',
        'logs' => 'LogsController',
        'payments' => 'PaymentsController',
        'contracts' => 'ContractsController',
        'special-dates' => 'SpecialDatesController',
        'lessons' => 'LessonsController',
        'email-messages' => 'EmailMessagesController',
        'tests' => 'TestsController',
        'client-tests' => 'ClientTestsController',
        'client-test-answers' => 'ClientTestAnswersController',
    ]);

    // Route::namespace('Test')->prefix('tests')->group(function() {
    //     Route::apiResources([
    //         '/' => 'TestsController',
    //     ]);
    // });

    Route::resource('upload', 'UploadController')->only(['store']);
    Route::resource('settings', 'SettingsController')->only(['index', 'store']);

    Route::resource('print', 'PrintController')->only(['index']);

    Route::prefix('sms')->group(function() {
        Route::get('/', 'SmsController@index');
        Route::post('send', 'SmsController@send');
    });

    Route::prefix('photo')->group(function() {
        Route::post('upload', 'PhotosController@upload');
        Route::post('crop', 'PhotosController@crop');
        Route::delete('{id}', 'PhotosController@destroy');
    });

    Route::get('rights', function() {
        return response()->json([
            'all' => \Shared\Rights::$all,
            'groups' => \Shared\Rights::$groups
        ]);
    });
});
