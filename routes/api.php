<?php

use Illuminate\Http\Request;

Route::namespace('Api\v1')->prefix('v1')->group(function() {

    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');

    Route::get('initial-data', 'InitialDataController@index');

    Route::delete('group-clients', 'GroupClientsController@destroy');
    Route::post('group-clients', 'GroupClientsController@store');

    Route::get('counters', 'CountersController@index');
    Route::get('search', 'SearchController@index');
    Route::get('visits', 'VisitsController@index');

    Route::get('preview/exit', 'PreviewController@exit');
    Route::get('preview', 'PreviewController@index');

    Route::get('balance', 'BalanceController@index');

    Route::get('schedule/client/{id}', 'ScheduleController@client');

    Route::post('lessons/conduct/{id}', 'LessonsController@conduct');

    Route::prefix('abstract-groups')->group(function() {
        Route::get('/', 'AbstractGroupsController@index');
        Route::get('/{year}/{grade_id}/{subject_id}', 'AbstractGroupsController@show');
    });

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
        'payment-additionals' => 'PaymentAdditionalsController',
        'contracts' => 'ContractsController',
        'special-dates' => 'SpecialDatesController',
        'lessons' => 'LessonsController',
        'client-lessons' => 'ClientLessonsController',
        'email-messages' => 'EmailMessagesController',
        'tests' => 'TestsController',
        'macros' => 'MacrosController',
        'client-tests' => 'ClientTestsController',
        'client-test-answers' => 'ClientTestAnswersController',
        'ege-trials' => 'EgeTrialsController',
        'reviews' => 'ReviewsController',
        'reports' => 'ReportsController',
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
