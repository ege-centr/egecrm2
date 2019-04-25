<?php

Route::get('/store', function () {
    $path = config('app.env') . '/' . date('Y-m-d') . '.json'; // local/2019-03-01.json
    Storage::disk('spaces')->put($path, json_encode(['foo' => 'bar']));
});

Route::get('download/{id}', 'DownloadController@download');
Route::get('/{any}', 'AppController@index')->where('any', '.*');
