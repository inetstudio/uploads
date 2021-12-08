<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use Illuminate\Http\File;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\RestoreResponseContract;

class RestoreResponse implements RestoreResponseContract
{
    public function __construct(
        protected ?File $result = null
    ){}

    public function setResult(?File $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        if (! $this->result) {
            abort(404);
        }

        return response($this->result->getContent())->withHeaders(
            [
                'Access-Control-Expose-Headers' => 'Content-Disposition, Content-Length, X-Content-Transfer-Id',
                'Content-Type' => $this->result->getMimeType(),
                'Content-Length' => $this->result->getSize(),
                'Content-Disposition' => 'inline; filename="'.$this->result->getFilename(). '"',
            ]
        );
    }
}
