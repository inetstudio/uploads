<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\PatchDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\PatchRequestContract;

class PatchRequest extends FormRequest implements PatchRequestContract
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [];
    }

    public function getDataObject(): PatchDataContract
    {
        $data = [
            'serverId' => $this->get('serverId'),
            'offset' => $this->header('Upload-Offset'),
            'length' => $this->header('Upload-Length'),
            'name' => $this->sanitizeFilename($this->header('Upload-Name') ?? ''),
            'content' => $this->getContent(),
        ];

        return resolve(
            PatchDataContract::class,
            [
                'args' => $data,
            ]
        );
    }

    protected function sanitizeFilename(string $fileName): string
    {
        $info = pathinfo($fileName);

        $name = $this->sanitizeFilenamePart($info['filename']);
        $extension = $this->sanitizeFilenamePart($info['extension'] ?? '');

        return (strlen($name) > 0 ? $name : '_') . '.' . $extension;
    }

    protected function sanitizeFilenamePart(string $str): string
    {
        return preg_replace("/[^a-zA-Z0-9\_\s]/", '', $str);
    }
}
