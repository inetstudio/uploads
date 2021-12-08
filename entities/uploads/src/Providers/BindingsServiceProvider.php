<?php

namespace InetStudio\UploadsPackage\Uploads\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\FetchActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\FetchAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\LoadActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\LoadAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\PatchActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\PatchAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\ProcessActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\ProcessAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RestoreActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\RestoreAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RevertActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\Back\RevertAction',
        'InetStudio\UploadsPackage\Uploads\Contracts\Actions\AttachMediaToObjectActionContract' => 'InetStudio\UploadsPackage\Uploads\Actions\AttachMediaToObjectAction',

        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\FetchDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\FetchData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\LoadDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\LoadData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\PatchDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\PatchData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\ProcessDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\ProcessData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RestoreDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\RestoreData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RevertDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\Back\RevertData',
        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\MediaDataContract' => 'InetStudio\UploadsPackage\Uploads\DTO\MediaData',

        'InetStudio\UploadsPackage\Uploads\Contracts\Events\Back\UpdateMediaEventContract' => 'InetStudio\UploadsPackage\Uploads\Events\Back\UpdateMediaEvent',

        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Controllers\Back\UploadsControllerContract' => 'InetStudio\UploadsPackage\Uploads\Http\Controllers\Back\UploadsController',

        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\FetchRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\FetchRequest',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\LoadRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\LoadRequest',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\PatchRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\PatchRequest',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\ProcessRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\ProcessRequest',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RestoreRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\RestoreRequest',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RevertRequestContract' => 'InetStudio\UploadsPackage\Uploads\Http\Requests\Back\RevertRequest',

        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\FetchResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\FetchResponse',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\LoadResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\LoadResponse',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\PatchResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\PatchResponse',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\ProcessResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\ProcessResponse',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RestoreResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\RestoreResponse',
        'InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RevertResponseContract' => 'InetStudio\UploadsPackage\Uploads\Http\Responses\Back\RevertResponse',

        'InetStudio\UploadsPackage\Uploads\Contracts\Services\Back\ImagesServiceContract' => 'InetStudio\UploadsPackage\Uploads\Services\Back\ImagesService',
    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
