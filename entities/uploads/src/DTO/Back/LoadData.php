<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\LoadDataContract;

class LoadData extends DataTransferObject implements LoadDataContract
{
    public int $id;
}
