<?php

namespace InetStudio\UploadsPackage\Uploads\Events\Back;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use InetStudio\UploadsPackage\Uploads\Contracts\Events\Back\UpdateMediaEventContract;

class UpdateMediaEvent implements UpdateMediaEventContract
{
    use SerializesModels;

    public function __construct(
        public Model $item,
        public string $collection
    ) {}
}
