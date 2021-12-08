<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RevertDataContract;

class RevertData extends DataTransferObject implements RevertDataContract
{
    public string $serverId;
}
