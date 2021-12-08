<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Responses\Back;

use Illuminate\Http\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Responses\Back\LoadResponseContract;

class LoadResponse implements LoadResponseContract
{
    public function __construct(
        protected ?Media $result = null
    ){}

    public function setResult(?Media $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        if (! $this->result) {
            abort(404);
        }

        $file = new File($this->result->getPath());

        return response($file->getContent())->withHeaders(
            [
                'Access-Control-Expose-Headers' => 'Content-Disposition, Content-Length, X-Content-Transfer-Id',
                'Content-Type' => $this->result->mime_type,
                'Content-Length' => $this->result->size,
                'Content-Disposition' => 'inline; filename="'.rawurlencode($this->result->name).'"',
            ]
        );
    }
}
