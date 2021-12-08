<?php

namespace InetStudio\UploadsPackage\Uploads\DTO\Back;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\ProcessDataContract;

class ProcessData extends DataTransferObject implements ProcessDataContract
{
    public ?UploadedFile $file;
}
