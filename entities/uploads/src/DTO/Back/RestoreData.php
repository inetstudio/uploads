<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RestoreDataContract;

class RestoreData extends DataTransferObject implements RestoreDataContract
{
    public string $serverId;
}
