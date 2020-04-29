<?php

namespace InetStudio\Uploads\Services\Back;

use Illuminate\Support\Str;
use InetStudio\Uploads\Contracts\Services\Back\FilesServiceContract;

/**
 * Class FilesService.
 */
class FilesService implements FilesServiceContract
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

                $fileName = Str::random().'.'.$request->file($name)->getClientOriginalExtension();

                $item->addMediaFromRequest($name)
                    ->usingFileName($fileName)
                    ->toMediaCollection($name, $disk);
            }
        }
    }
}
