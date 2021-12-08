<?php

namespace InetStudio\UploadsPackage\Uploads\Actions;

use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\MediaDataContract;
use Spatie\MediaLibrary\MediaCollections\Events\CollectionHasBeenCleared;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\AttachMediaToObjectActionContract;

class AttachMediaToObjectAction implements AttachMediaToObjectActionContract
{
    public function __construct(
        protected Model $item,
        protected array $media
    ) {}

    public function execute(): Model
    {
        $media = $this->prepareMedia($this->media);

        $media->each(function ($collections) {
            $collections->each(function ($mediaItems, $collection) {
                if ($mediaItems->count() === 0) {
                    $this->clearMediaCollection($collection);
                } else {
                    $this->clearMediaCollectionExcept($collection, $mediaItems);

                    $mediaItems->each(function ($mediaItem) {
                        if ($mediaItem->id === 0) {
                            $this->createMedia($mediaItem);
                        } else {
                            $this->updateMedia($mediaItem);
                        }
                    });
                }
            });
        });

        return $this->item;
    }

    protected function prepareMedia(array $media): Collection
    {
        return collect($media)
            ->mapWithKeys(function ($collections, $field) {
                $data = collect($collections)
                    ->mapWithKeys(function ($mediaItems, $collection) {
                        if (is_string($mediaItems)) {
                            $mediaItems = (array) json_decode($mediaItems, true);
                        }

                        $mediaItems = collect($mediaItems)
                            ->map(function ($mediaItem) {
                                return ($mediaItem instanceof MediaDataContract)
                                    ? $mediaItem
                                    : resolve(
                                        'InetStudio\UploadsPackage\Uploads\Contracts\DTO\MediaDataContract',
                                        [
                                            'args' => $mediaItem,
                                        ]
                                    );
                            })
                            ->filter(function ($mediaItem) {
                                return ($mediaItem->id || $mediaItem->serverId);
                            });

                        return [$collection => $mediaItems];
                    });

                return [$field => $data];
            });
    }

    protected function clearMediaCollection(string $collectionName): void
    {
        $this->item->getMedia($collectionName)
            ->each(function ($media) {
                $media->collection_name = $media->collection_name.'_deleted';
                $media->save();
            });

        event(new CollectionHasBeenCleared($this->item, $collectionName));

        if ($this->item->relationLoaded('media')) {
            unset($this->item->media);
        }
    }

    protected function clearMediaCollectionExcept(string $collectionName, Collection $excludedMedia): void
    {
        if ($excludedMedia->isEmpty()) {
            $this->clearMediaCollection($collectionName);

            return;
        }

        $this->item->getMedia($collectionName)
            ->reject(function (Media $media) use ($excludedMedia) {
                return $excludedMedia->where($media->getKeyName(), $media->getKey())->count();
            })
            ->each(function ($media) {
                $media->collection_name = $media->collection_name.'_deleted';
                $media->save();
            });

        if ($this->item->relationLoaded('media')) {
            unset($this->item->media);
        }

        if ($this->item->getMedia($collectionName)->isEmpty()) {
            event(new CollectionHasBeenCleared($this->item, $collectionName));
        }
    }

    protected function createMedia(MediaDataContract $mediaItem): void
    {
        $file = $this->getFile($mediaItem);

        $this->item->addMedia($file)
            ->withManipulations($mediaItem->manipulations)
            ->withCustomProperties($mediaItem->custom_properties)
            ->usingName($this->sanitizeName($mediaItem->name))
            ->usingFileName($file->getFilename())
            ->storingConversionsOnDisk($mediaItem->conversions_disk)
            ->toMediaCollection($mediaItem->collection_name, $mediaItem->disk);

        $this->removeTempDirectory($mediaItem);
    }

    protected function sanitizeName(string $filename): string
    {
        $info = pathinfo($filename);

        $name = preg_replace("/[^a-zA-Zа-яА-Я0-9\_\s]/u", '', $info['filename']);
        $extension = preg_replace("/[^a-zA-Zа-яА-Я0-9\_\s]/u", '', $info['extension']);

        return (strlen($name) > 0 ? $name : '_') . '.' . $extension;
    }

    protected function getFile(MediaDataContract $mediaItem): ?File
    {
        if ($mediaItem->serverId === '') {
            return null;
        }

        $files = Storage::disk('temp')->files($mediaItem->serverId);

        if (empty($files)) {
            return null;
        }

        return new File(Storage::disk('temp')->path($files[0]));
    }

    protected function removeTempDirectory(MediaDataContract $mediaItem): void
    {
        Storage::disk('temp')->deleteDirectory($mediaItem->serverId);
    }

    protected function updateMedia(MediaDataContract $mediaItem): void
    {
        $media = Media::find($mediaItem->id);

        if (! $media) {
            return;
        }

        $media->collection_name = $mediaItem->collection_name;
        $media->name = $mediaItem->name;
        $media->file_name = $mediaItem->file_name;
        $media->mime_type = $mediaItem->mime_type;
        $media->disk = $mediaItem->disk;
        $media->manipulations = $mediaItem->manipulations;
        $media->custom_properties = $mediaItem->custom_properties;

        $media->save();
    }
}
