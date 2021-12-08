<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\PatchDataContract;

class PatchData extends DataTransferObject implements PatchDataContract
{
    public string $serverId;

    public int $offset;

    public int $length;

    public string $name;

    public string $content;
}
