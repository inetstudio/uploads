<?php

namespace InetStudio\UploadsPackage\Uploads\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\MediaDataContract;

class MediaData extends DataTransferObject implements MediaDataContract
{
    public int|string|null $id = 0;

    public string $serverId = '';

    public string $collection_name = '';

    public string $name = '';

    public string $file_name = '';

    public string $mime_type = '';

    public string $disk = '';

    public string $conversions_disk = '';

    public array $manipulations = [];

    public array $custom_properties = [];
}
