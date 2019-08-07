<?php

Route::get('download/{id}', 'DownloadController@download');
Route::get('/{any}', 'AppController@index')->where('any', '.*');
