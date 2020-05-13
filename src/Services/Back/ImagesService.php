<?php

namespace InetStudio\Uploads\Services\Back;

use DOMDocument;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract;
use Spatie\MediaLibrary\MediaCollections\Events\CollectionHasBeenCleared;

/**
 * Class ImagesService.
 */
class ImagesService implements ImagesServiceContract
{
    /**
     * Сохраняем изображения.
     *
     * @param $request
     * @param $item
     * @param  array  $images
     * @param  string  $disk
     * @param  string  $model
     */
    public function attachToObject($request, $item, array $images, string $disk, string $model = ''): void
    {
        $model = ($model) ? '.'.$model : '';

        foreach ($images as $requestName => $name) {
            $properties = (is_numeric($requestName)) ? $request->get($name) : $request->input($requestName);

            if (! $properties) {
                continue;
            }

            event(app()->makeWith('InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract', [
                'object' => $item,
                'collection' => $name,
            ]));

            if (isset($properties['has_images']) && ! isset($properties['images'])) {
                $this->clearMediaCollection($item, $name);
            } elseif (isset($properties['images'])) {
                $this->clearMediaCollectionExcept($item, $name, $properties['images']);

                foreach ($properties['images'] as $image) {
                    if ($image['id']) {
                        $media = $item->media->find($image['id']);
                        if ($media) {
                            $media->custom_properties = $image['properties'];
                            $media->save();
                        }
                    } else {
                        $filename = $image['filename'];

                        $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image['tempname'];

                        $media = $item->addMedia($file)
                            ->withCustomProperties($image['properties'])
                            ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                            ->usingFileName($image['tempname'])
                            ->toMediaCollection($name, $disk);
                    }

                    $imagePath = ($media->mime_type == 'image/gif') ? $media->getFullUrl() : $media->getFullUrl($name.'_front');

                    $src = $this->getImagesSrc($item[$name]);
                    $srcReplaces = $this->getImageSrcReplaces($src);

                    $item->update([
                        $name => str_replace($image['src'], $imagePath,
                            str_replace(array_keys($srcReplaces), array_values($srcReplaces), $item[$name])),
                    ]);
                }
            } else {
                $manipulations = [];

                if (isset($properties['crop']) && config($disk.'.images.conversions'.$model)) {
                    foreach ($properties['crop'] as $key => $cropJSON) {
                        $json = str_replace('\\', '', $cropJSON);
                        $json = trim($json, '"');

                        $cropData = json_decode($json, true);

                        if (! $cropData) {
                            continue;
                        }

                        foreach (config($disk.'.images.conversions'.$model.'.'.$name.'.'.$key) as $conversion) {
                            if (isset($conversion['skip_manipulations']) && $conversion['skip_manipulations']) {
                                continue;
                            }

                            event(app()->makeWith('InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract',
                                [
                                    'object' => $item,
                                    'collection' => $conversion['name'],
                                ]));

                            $manipulations[$conversion['name']] = [
                                'manualCrop' => implode(',', [
                                    round($cropData['width']),
                                    round($cropData['height']),
                                    round($cropData['x']),
                                    round($cropData['y']),
                                ]),
                            ];
                        }
                    }
                }

                if (isset($properties['tempname']) && isset($properties['filename']) && $properties['tempname'] != '' && $properties['filename'] != '') {
                    $image = $properties['tempname'];
                    $filename = $properties['filename'];

                    $this->clearMediaCollection($item, $name);

                    Arr::forget($properties, ['tempname', 'temppath', 'filepath', 'filename']);
                    $properties = array_filter($properties);

                    $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image;

                    $item->addMedia($file)
                        ->withManipulations($manipulations)
                        ->withCustomProperties($properties)
                        ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                        ->usingFileName($image)
                        ->toMediaCollection($name, $disk);
                } else {
                    $properties = array_filter($properties);

                    $media = $item->getFirstMedia($name);

                    if ($media) {
                        $media->custom_properties = $properties;
                        $media->manipulations = $manipulations;
                        $media->save();
                    }
                }
            }
        }
    }

    /**
     * Получаем первый кроп изображения.
     *
     * @param $item
     * @param  string  $collection
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFirstCropImageUrl($item, string $collection)
    {
        $media = $item->getFirstMedia($collection);

        if ($media) {
            $crops = $media->getCustomProperty('crop');

            foreach ($crops as $name => $cropData) {
                return url($media->getUrl($collection.'_'.$name));
            }

            return url($media->getUrl());
        }

        return '';
    }

    /**
     * Получаем первый кроп изображения.
     *
     * @param $item
     * @param  string  $collection
     *
     * @return array
     */
    public function getImageProperties($item, string $collection): array
    {
        $media = $item->getFirstMedia($collection);

        if ($media && $media->custom_properties) {
            $properties = $media->custom_properties;

            Arr::forget($properties, 'crop');

            return $properties;
        }

        return [];
    }

    /**
     * Получаем все пути на изображения.
     *
     * @param  string  $content
     *
     * @return array
     */
    protected function getImagesSrc($content): array
    {
        if (! trim($content)){
            return [];
        }

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        libxml_use_internal_errors(false);

        $tags = $doc->getElementsByTagName('img');

        $src = [];

        foreach ($tags as $tag) {
            $src[] = $tag->getAttribute('src');
        }

        return $src;
    }

    /**
     * Заменяем пути на абсолютные.
     *
     * @param  array  $src
     *
     * @return array
     */
    protected function getImageSrcReplaces(array $src): array
    {
        $replaces = [];

        foreach ($src as $imageSrc) {
            if (Str::contains($imageSrc, '/storage/')) {
                $path = Str::after($imageSrc, '/storage/');
                $replaces[$imageSrc] = url('storage/'.$path);
            }
        }

        return $replaces;
    }

    /**
     * Remove all media in the given collection.
     *
     * @param $item
     * @param string $collectionName
     *
     * @return mixed
     */
    public function clearMediaCollection($item, string $collectionName = 'default')
    {
        $item->getMedia($collectionName)
            ->each(function ($item, $key) {
                $item->collection_name = $item->collection_name.'_deleted';
                $item->save();
            });

        event(new CollectionHasBeenCleared($item, $collectionName));

        if ($item->relationLoaded('media')) {
            unset($item->media);
        }

        return $item;
    }

    /**
     * Remove all media in the given collection except some.
     *
     * @param $item
     * @param  string  $collectionName
     * @param  array  $excludedMedia
     *
     * @return mixed
     */
    protected function clearMediaCollectionExcept($item, string $collectionName = 'default', $excludedMedia = [])
    {
        if ($excludedMedia instanceof Media) {
            $excludedMedia = collect()->push($excludedMedia);
        }

        $excludedMedia = collect($excludedMedia);

        if ($excludedMedia->isEmpty()) {
            return $this->clearMediaCollection($collectionName);
        }

        $item->getMedia($collectionName)
            ->reject(function (Media $media) use ($excludedMedia) {
                return $excludedMedia->where($media->getKeyName(), $media->getKey())->count();
            })
            ->each(function ($item, $key) {
                $item->collection_name = $item->collection_name.'_deleted';
                $item->save();
            });

        if ($item->relationLoaded('media')) {
            unset($item->media);
        }

        if ($item->getMedia($collectionName)->isEmpty()) {
            event(new CollectionHasBeenCleared($item, $collectionName));
        }

        return $item;
    }
}
