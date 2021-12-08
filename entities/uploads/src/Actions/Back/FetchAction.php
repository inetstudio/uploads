<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\FetchDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\FetchActionContract;

class FetchAction implements FetchActionContract
{
    public function execute(FetchDataContract $data): array
    {
        $serverId = Str::random();

        $fileContents = file_get_contents($data->url);
        $fileName = basename($data->url);
        $filePath = $serverId . '/' . Str::random().'.'.pathinfo($fileName, PATHINFO_EXTENSION);

        Storage::disk('temp')->put($filePath, $fileContents);

        return [
            'file' => new File(Storage::disk('temp')->path($filePath)),
            'sourceName' => $fileName,
            'serverId' => $filePath,
        ];
    }
}
