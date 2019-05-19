<?php
	Route::get('test', function () {
		return response('works', 422);
	});
Route::get('download/{id}', 'DownloadController@download');
Route::get('/{any}', 'AppController@index')->where('any', '.*');
