<?php

namespace InetStudio\UploadsPackage\Uploads\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class CropSize implements Rule
{
    protected $aliases = [
        'min' => 'Минимальный',
        'fix' => 'Фиксированный',
    ];

    public function __construct(
        protected int $width,
        protected int $height,
        protected string $mode,
        protected string $cropName
    ) {}

    public function passes($attribute, $value)
    {
        $json = str_replace('\\', '', $value);
        $json = trim($json, '"');

        $crop = json_decode($json, true);

        switch ($this->mode) {
            case 'min':
                if (round($crop['width']) < $this->width || round($crop['height']) < $this->height) {
                    return false;
                }
                break;
            case 'fixed':
                if (round($crop['width']) != $this->width && round($crop['height']) != $this->height) {
                    return false;
                }
                break;
        }

        return true;
    }

    public function message()
    {
        $cropName = ($this->cropName !== '') ? ' «'.$this->cropName.'» ' : ' ';

        return $this->aliases[$this->mode].' размер области'.$cropName.'— '.$this->width.'x'.$this->height.' пикселей';
    }
}
