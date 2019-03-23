<?php

namespace InetStudio\Uploads\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class UploadsBindingsServiceProvider.
 */
class UploadsBindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract' => 'InetStudio\Uploads\Events\Back\UpdateUploadEvent',
        'InetStudio\Uploads\Contracts\Exceptions\UploadExceptionContract' => 'InetStudio\Uploads\Exceptions\UploadException',
        'InetStudio\Uploads\Contracts\Http\Controllers\Back\UploadsControllerContract' => 'InetStudio\Uploads\Http\Controllers\Back\UploadsController',
        'InetStudio\Uploads\Contracts\Services\Back\FilesServiceContract' => 'InetStudio\Uploads\Services\Back\FilesService',
        'InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract' => 'InetStudio\Uploads\Services\Back\ImagesService',
        'InetStudio\Uploads\Contracts\Services\UploaderServiceContract' => 'InetStudio\Uploads\Services\UploaderService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
