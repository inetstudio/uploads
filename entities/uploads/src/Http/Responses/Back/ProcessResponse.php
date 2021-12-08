<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\ProcessResponseContract;

class ProcessResponse implements ProcessResponseContract
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
        return response($this->result, 201)
            ->withHeaders(
                [
                    'Content-Type' => 'text/plain',
                ]
            );
    }
}
