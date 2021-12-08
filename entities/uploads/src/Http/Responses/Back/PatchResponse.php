<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\PatchResponseContract;

class PatchResponse implements PatchResponseContract
{
    public function __construct(
        protected string $result = ''
    ){}

    public function setResult(string $result): void
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
