<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\LoadDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\LoadActionContract;

class LoadAction implements LoadActionContract
{
    public function execute(LoadDataContract $data): ?Media
    {
        return Media::find($data->id);
    }
}
