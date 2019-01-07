<?php

namespace InetStudio\Uploads\Services;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use InetStudio\Uploads\Contracts\Services\UploaderServiceContract;

/**
 * Class UploaderService.
 */
class UploaderService implements UploaderServiceContract
{
    private $maxFileAge = 600; //600 seconds
    protected $request;

    /**
     * UploaderService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath()
    {
        $path = storage_path('uploads/');

        return $path;
    }

    public function receiveSingle($name, Closure $handler)
    {
        if ($this->request->file($name)) {
            return $handler($this->request->file($name));
        }

        return false;
    }

    private function appendData($filePathPartial, UploadedFile $file)
    {
        if (! $out = @fopen($filePathPartial, 'ab')) {
            throw app()->makeWith('InetStudio\Uploads\Contracts\Exceptions\UploadExceptionContract', [
                'message' => 'Failed to open output stream.',
                'code' => 102,
                'previous' => null,
            ]);
        }

        if (! $in = @fopen($file->getPathname(), 'rb')) {
            throw app()->makeWith('InetStudio\Uploads\Contracts\Exceptions\UploadExceptionContract', [
                'message' => 'Failed to open input stream.',
                'code' => 101,
                'previous' => null,
            ]);
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
    }

    public function receiveChunks($name, Closure $handler)
    {
        $result = false;

        if ($this->request->file($name)) {
            $file = $this->request->file($name);
            $chunk = (int) $this->request->get('chunk', false);
            $chunks = (int) $this->request->get('chunks', false);
            $originalName = $this->request->get('name');

            $filePath = $this->getPath().'/'.$originalName.'.part';
            $this->removeOldData($filePath);

            $this->appendData($filePath, $file);

            if ($chunk == $chunks - 1) {
                $file = new UploadedFile($filePath, $originalName, 'blob', filesize($filePath), UPLOAD_ERR_OK, true);

                $result = $handler($file);

                @unlink($filePath);
            }
        }

        return $result;
    }

    public function removeOldData($filePath)
    {
        if (file_exists($filePath) && filemtime($filePath) < time() - $this->maxFileAge) {
            @unlink($filePath);
        }
    }

    public function hasChunks()
    {
        return (bool) $this->request->get('chunks', false);
    }

    public function receive($name, Closure $handler)
    {
        $response = [];
        $response['jsonrpc'] = '2.0';

        if ($this->hasChunks()) {
            $result = $this->receiveChunks($name, $handler);
        } else {
            $result = $this->receiveSingle($name, $handler);
        }

        $response['result'] = $result;

        return $response;
    }
}
