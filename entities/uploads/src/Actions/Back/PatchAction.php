<?php

namespace InetStudio\UploadsPackage\Uploads\Actions\Back;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InetStudio\UploadsPackage\Uploads\Contracts\DTO\Back\PatchDataContract;
use InetStudio\UploadsPackage\Uploads\Contracts\Actions\Back\PatchActionContract;

class PatchAction implements PatchActionContract
{
    public function execute(PatchDataContract $data): string
    {
        $patchPath = $data->serverId.'/'.'.patch.' . $data->offset;

        Storage::disk('temp')->put($patchPath, $data->content);

        $patches = $this->getPatches($data->serverId);
        $patchesSize = $this->getPatchesSize($patches);

        if ($patchesSize == $data->length) {

            $filePath = $data->serverId . '/' . Str::random().'.'.Str::afterLast($data->name, '.');

            $fullFilePath = Storage::disk('temp')->path($filePath);

            $resultFileHandle = fopen($fullFilePath, 'w');

            foreach ($patches as $patch) {
                $offset = (int) Str::afterLast($patch->getFileName(), '.');
                $content = $patch->getContent();

                fseek($resultFileHandle, $offset);
                fwrite($resultFileHandle, $content);
            }

            fclose($resultFileHandle);

            $this->removePatches($data->serverId, $patches);

            return $filePath;
        }

        return $data->serverId;
    }

    protected function getPatches(string $serverId): array
    {
        $files = Storage::disk('temp')->allFiles($serverId);

        $patches = [];
        foreach ($files as $filePath) {
            $fullFilePath = Storage::disk('temp')->path($filePath);

            $file = new File($fullFilePath);

            if (! Str::is('.patch.*', $file->getFilename())) {
                continue;
            }

            $patches[] = $file;
        }

        return $patches;
    }

    protected function getPatchesSize(array $patches): int
    {
        $size = 0;
        foreach ($patches as $patch) {
            $size += $patch->getSize();
        }

        return $size;
    }

    protected function removePatches(string $serverId, array $patches): void
    {
        foreach ($patches as $patch) {
            Storage::disk('temp')->delete($serverId.'/'.$patch->getFilename());
        }
    }
}
