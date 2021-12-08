<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\FetchResponseContract;

class FetchResponse implements FetchResponseContract
{
    public function __construct(
        protected array $result = []
    ){}

    public function setResult(array $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        return response($this->result['file']->getContent())->withHeaders(
            [
                'Access-Control-Expose-Headers' => 'Content-Disposition, Content-Length, X-Content-Transfer-Id',
                'Content-Type' => $this->result['file']->getMimeType(),
                'Content-Length' => $this->result['file']->getSize(),
                'Content-Disposition' => 'inline; filename="'.$this->result['sourceName']. '"',
                'X-Content-Transfer-Id' => $this->result['serverId'],
            ]
        );
    }
}
