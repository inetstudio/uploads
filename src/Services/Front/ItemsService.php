<?php

namespace InetStudio\Uploads\Services\Front;

use Illuminate\Support\Str;
use InetStudio\Uploads\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService implements ItemsServiceContract
{
    /**
     * Аттачим файлы к модели.
     *
     * @param $item
     * @param array $files
     * @param string $disk
     */
    public function attachFilesToObject($item, array $files, string $disk): void
    {
        foreach ($files as $collection => $filesArr) {
            foreach ($filesArr as $file) {
                $fileName = Str::random().'.'.$file->guessExtension();

                $item->addMedia($file)
                    ->usingFileName($fileName)
                    ->toMediaCollection($collection, $disk);
            }
        }
    }
}
