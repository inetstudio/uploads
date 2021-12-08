<?php

namespace InetStudio\UploadsPackage\Uploads\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\RevertDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Http\Requests\Back\RevertRequestContract;

class RevertRequest extends FormRequest implements RevertRequestContract
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

    public function getDataObject(): RevertDataContract
    {
        $data = [
            'serverId' => $this->getContent(),
        ];

        return resolve(
            RevertDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
