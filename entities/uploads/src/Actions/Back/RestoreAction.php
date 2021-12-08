<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RestoreDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\RestoreActionContract;

class RestoreAction implements RestoreActionContract
{
    public function execute(RestoreDataContract $data): ?File
    {
        $files = Storage::disk('temp')->files($data->serverId);

        if (empty($files)) {
            return null;
        }

        return new File(Storage::disk('temp')->path($files[0]));
    }
}
