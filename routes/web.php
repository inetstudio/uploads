<?php

Route::group([
    'namespace' => 'InetStudio\Uploads\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::post('upload', 'UploadsControllerContract@upload')->name('back.upload');
});
