<?php

namespace InetStudio\Uploads\Services\Back;

use InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract;

/**
 * Class FilesService.
 */
class FilesService implements ImagesServiceContract
{
    /**
     * Сохраняем файлы.
     *
     * @param $item
     * @param array $files
     * @param string $disk
     */
    public function attachToObject($item, array $files, string $disk): void
    {
        $request = request();

        foreach ($files as $name) {
            if ($request->has($name)) {
                $item->clearMediaCollection($name);

                $item->addMediaFromRequest($name)
                    ->toMediaCollection($name, $disk);
            }
        }
    }
}
