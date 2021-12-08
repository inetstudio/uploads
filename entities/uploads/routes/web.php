<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'InetStudio\UploadsPackage\Uploads\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back/uploads-package',
], function () {
    Route::get('uploads/fetch', 'UploadsControllerContract@fetch')->name('inetstudio.uploads-package.uploads.back.fetch');
    Route::get('uploads/load', 'UploadsControllerContract@load')->name('inetstudio.uploads-package.uploads.back.load');
    Route::patch('uploads/patch', 'UploadsControllerContract@patch')->name('inetstudio.uploads-package.uploads.back.patch');
    Route::post('uploads/process', 'UploadsControllerContract@processUpload')->name('inetstudio.uploads-package.uploads.back.process');
    Route::get('uploads/restore', 'UploadsControllerContract@restore')->name('inetstudio.uploads-package.uploads.back.restore');
    Route::delete('uploads/revert', 'UploadsControllerContract@revert')->name('inetstudio.uploads-package.uploads.back.revert');
});
