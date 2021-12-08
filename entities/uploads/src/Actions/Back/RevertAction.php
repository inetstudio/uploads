<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RevertDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RevertActionContract;

class RevertAction implements RevertActionContract
{
    public function execute(RevertDataContract $data): bool
    {
        $serverId = Str::before($data->serverId, '/');

        return Storage::disk('temp')->deleteDirectory($serverId);
    }
}
