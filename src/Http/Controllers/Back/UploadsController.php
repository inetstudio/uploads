<?php

namespace InetStudio\Uploads\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use InetStudio\Uploads\Contracts\Http\Controllers\Back\UploadsControllerContract;

/**
 * Class UploadsController.
 */
class UploadsController extends Controller implements UploadsControllerContract
{
    /**
     * Загружаем файл во временную директорию.
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request): array
    {
        $transformName = str_replace(['.', '[]', '[', ']'], ['_', '.0', '.', ''], $request->get('fieldName'));

        return app()->makeWith('InetStudio\Uploads\Contracts\Services\UploaderServiceContract', [
            'request' => $request,
        ])->receive($transformName, function ($file) {
            $tempName = Storage::disk('temp')->putFile('', $file, 'public');

            Storage::delete($file->path());

            return [
                'tempName' => $tempName,
                'tempPath' => url(Storage::disk('temp')->url($tempName)),
            ];
        });
    }
}
