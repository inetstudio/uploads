<?php

namespace InetStudio\UploadsPackage\Uploads\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class CropRequired implements Rule
{
    public function passes($attribute, $value)
    {
        $filenameInput = str_replace('crop', 'filename', $attribute);

        if (request()->input($filenameInput) === null) {
            return true;
        }

        foreach ($value as $values) {
            if (! empty($values)) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'Необходимо выбрать область отображения';
    }
}
