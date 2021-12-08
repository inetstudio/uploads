<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\FetchDataContract;

class FetchData extends DataTransferObject implements FetchDataContract
{
    public string $url;
}
