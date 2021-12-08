<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\FetchDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\FetchRequestContract;

class FetchRequest extends FormRequest implements FetchRequestContract
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

    public function getDataObject(): FetchDataContract
    {
        $data = [
            'url' => $this->get('url'),
        ];

        return resolve(
            FetchDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
