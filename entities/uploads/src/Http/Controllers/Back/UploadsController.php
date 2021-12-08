<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\LoadActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\FetchActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\PatchActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RevertActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\ProcessActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RestoreActionContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\LoadRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\FetchRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\PatchRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\LoadResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RevertRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\FetchResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\PatchResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\ProcessRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RestoreRequestContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RevertResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\ProcessResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RestoreResponseContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Controllers\Back\UploadsControllerContract;

class UploadsController extends Controller implements UploadsControllerContract
{
    public function fetch(FetchRequestContract $request, FetchActionContract $action, FetchResponseContract $response): FetchResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function load(LoadRequestContract $request, LoadActionContract $action, LoadResponseContract $response): LoadResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function patch(PatchRequestContract $request, PatchActionContract $action, PatchResponseContract $response): PatchResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function processUpload(ProcessRequestContract $request, ProcessActionContract $action, ProcessResponseContract $response): ProcessResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function restore(RestoreRequestContract $request, RestoreActionContract $action, RestoreResponseContract $response): RestoreResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function revert(RevertRequestContract $request, RevertActionContract $action, RevertResponseContract $response): RevertResponseContract
    {
        return $this->process($request, $action, $response);
    }
}
