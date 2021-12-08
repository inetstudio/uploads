<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\ProcessDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\ProcessRequestContract;

class ProcessRequest extends FormRequest implements ProcessRequestContract
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

    public function getDataObject(): ProcessDataContract
    {
        $data = [
            'file' => $this->file('filepond'),
        ];

        return resolve(
            ProcessDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
