<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\ProcessDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\ProcessActionContract;

class ProcessAction implements ProcessActionContract
{
    public function execute(ProcessDataContract $data): string
    {
        $serverId = Str::random();

        if ($data->file) {
            $serverId = Storage::disk('temp')->putFile($serverId, $data->file);
        } else {
            Storage::disk('temp')->makeDirectory($serverId);
        }

        return $serverId;
    }
}
