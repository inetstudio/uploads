<?php

namespace InetStudio\Uploads\Models\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Trait HasImages.
 */
trait HasImages
{
    use InteractsWithMedia;

    public $registerMediaConversionsUsingModelInstance = true;

    /**
     * Регистрируем преобразования изображений.
     *
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     *
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $config = $this->images['config'] ?? '';
        $model = ($this->images['model']) ? $this->images['model'] : $this->material_type;

        if (! $config) {
            return;
        }

        $quality = (config($config.'.images.quality')) ? config($config.'.images.quality') : 75;

        if (config($config.'.images.conversions.'.$model)) {
            foreach (config($config.'.images.conversions.'.$model) as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $imageConversion = $this->addMediaConversion($conversion['name']);

                        if (isset($conversion['size']['width'])) {
                            $imageConversion->width($conversion['size']['width']);
                        }

                        if (isset($conversion['size']['height'])) {
                            $imageConversion->height($conversion['size']['height']);
                        }

                        if (isset($conversion['crop']['width']) && isset($conversion['crop']['height'])) {
                            $imageConversion->crop('crop-center', $conversion['crop']['width'], $conversion['crop']['height']);
                        }

                        if (isset($conversion['fit']['width']) && isset($conversion['fit']['height'])) {
                            $fitMethod = (isset($conversion['fit']['method'])) ? $conversion['fit']['method'] : 'fill';

                            $imageConversion->fit($fitMethod, $conversion['fit']['width'], $conversion['fit']['height']);

                            if ($fitMethod == 'fill' && isset($conversion['fit']['background'])) {
                                $imageConversion->background($conversion['fit']['background']);
                            }
                        }

                        if (isset($conversion['quality'])) {
                            $imageConversion->quality($conversion['quality']);
                            $imageConversion->optimize();
                        } else {
                            $imageConversion->quality($quality);
                        }

                        if (isset($conversion['format'])) {
                            $imageConversion->format($conversion['format']);
                        }

                        foreach ($conversion['effects'] ?? [] as $effect => $value) {
                            $imageConversion->$effect($value);
                        }

                        if (isset($conversion['watermark'])) {
                            $processWatermark = false;

                            if (isset($conversion['watermark']['copyright'])) {
                                $needle = trim(strtolower($conversion['watermark']['copyright']));
                                $imageCopyright = trim(strtolower($media->getCustomProperty('copyright', '')));

                                if ($imageCopyright == $needle) {
                                    $processWatermark = true;
                                }
                            } else {
                                $processWatermark = true;
                            }

                            if ($processWatermark) {
                                $imageConversion->watermark(storage_path('app/public/'.$conversion['watermark']['image']));

                                if (isset($conversion['watermark']['opacity'])) {
                                    $imageConversion->watermarkOpacity($conversion['watermark']['opacity']);
                                }

                                $imageConversion->watermarkPadding(1, 1, Manipulations::UNIT_PERCENT);

                                if (isset($conversion['watermark']['width'])) {
                                    $imageConversion->watermarkWidth($conversion['watermark']['width'], Manipulations::UNIT_PERCENT);
                                }
                            }
                        }

                        if (isset($conversion['responsive'])) {
                            $imageConversion->withResponsiveImages();
                        }

                        $imageConversion->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
