<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\LoadDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\LoadRequestContract;

class LoadRequest extends FormRequest implements LoadRequestContract
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

    public function getDataObject(): LoadDataContract
    {
        $data = [
            'id' => $this->get('id'),
        ];

        return resolve(
            LoadDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
