<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RevertResponseContract;

class RevertResponse implements RevertResponseContract
{
    public function __construct(
        protected bool $result = false
    ){}

    public function setResult(bool $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        return response('', 204)
            ->withHeaders(
                [
                    'Content-Type' => 'text/plain',
                ]
            );
    }
}
