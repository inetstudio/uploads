<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RestoreDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RestoreRequestContract;

class RestoreRequest extends FormRequest implements RestoreRequestContract
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

    public function getDataObject(): RestoreDataContract
    {
        $data = [
            'serverId' => $this->get('serverId'),
        ];

        return resolve(
            RestoreDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
