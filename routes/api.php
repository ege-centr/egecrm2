<?php

use Illuminate\Http\Request;

Route::namespace('Api\v1')->prefix('v1')->group(function() {
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');

    Route::prefix('reset-password')->group(function () {
        Route::post('send-code', 'ResetPasswordController@sendCode');
        Route::post('verify-code', 'ResetPasswordController@verifyCode');
        Route::post('change-password', 'ResetPasswordController@changePassword');
    });

    Route::get('initial-data', 'InitialDataController@index');

    Route::middleware('login')->group(function () {

        // пустой action для продолжения сессии
        Route::get('continue-session', function () { });

        Route::post('confirm-password', 'LoginController@confirmPassword');

        Route::get('person', 'PersonController@index');

        Route::delete('group-clients', 'GroupClientsController@destroy');
        Route::post('group-clients', 'GroupClientsController@store');

        Route::get('counters', 'CountersController@index');
        Route::get('search', 'SearchController@index');
        Route::get('visits', 'VisitsController@index');

        Route::get('preview-mode/exit', 'PreviewModeController@exit');
        Route::get('preview-mode', 'PreviewModeController@index');

        Route::get('balance', 'BalanceController@index');

        Route::get('schedule/client/{id}', 'ScheduleController@client');

        Route::post('lessons/mass-clone', 'LessonsController@massClone');
        Route::post('lessons/mass-destroy', 'LessonsController@massDestroy');

        Route::post('lessons/conduct/{id}', 'LessonsController@conduct');


        Route::prefix('abstract-groups')->group(function() {
            Route::get('/', 'AbstractGroupsController@index');
            Route::get('/{year}/{grade_id}/{subject_id}', 'AbstractGroupsController@show');
        });

        Route::get('cabinets/occupied', 'CabinetsController@occupied');
        Route::post('cabinets/schedule', 'CabinetsController@schedule');

        Route::prefix('timeline')->group(function () {
            foreach(['group', 'cabinet', 'teacher'] as $name) {
                Route::post($name, "TimelineController@{$name}");
            }
        });

        Route::apiResources([
            'teacher/freetime' => 'TeacherFreetimeController',
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
            'reviews' => 'ReviewsController',
            'reports' => 'ReportsController',
            'tables' => 'TablesController',
            'sms/templates' => 'Sms\TemplatesController',
        ]);

        // Route::namespace('Test')->prefix('tests')->group(function() {
        //     Route::apiResources([
        //         '/' => 'TestsController',
        //     ]);
        // });

        Route::resource('upload', 'UploadController')->only(['store']);
        Route::resource('settings', 'SettingsController')->only(['index', 'store']);

        Route::resource('print', 'PrintController')->only(['index']);

        Route::prefix('sms/messages')->group(function() {
            Route::get('/', 'Sms\MessagesController@index');
            Route::post('send', 'Sms\MessagesController@send');
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
});
