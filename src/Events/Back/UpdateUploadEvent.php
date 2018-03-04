<?php

namespace InetStudio\Uploads\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract;

/**
 * Class UpdateUploadEvent.
 */
class UpdateUploadEvent implements UpdateUploadEventContract
{
    use SerializesModels;

    public $object;
    public $collection;

    /**
     * Create a new event instance.
     *
     * UpdateUploadEvent constructor.
     *
     * @param $object
     * @param string $collection
     */
    public function __construct($object, $collection)
    {
        $this->object = $object;
        $this->collection = $collection;
    }
}
