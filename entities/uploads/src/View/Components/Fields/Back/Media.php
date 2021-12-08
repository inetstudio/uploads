<?php

namespace InetStudio\UploadsPackage\Uploads\View\Components\Fields\Back;

use stdClass;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Model;

class Media extends Component
{
    public function __construct(
        public string $fieldName,
        public Model $item,
        public string $label = ''
    ) {
        $this->fieldName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $fieldName);
    }

    public function render()
    {
        $data = $this->getMediaData();

        if (empty($data)) {
            return '';
        }

        return view('inetstudio.uploads-package.uploads::back.components.fields.media', compact('data'));
    }

    public function getMediaData(): array
    {
        $mediaConfig = $this->item->getMediaConfig();

        $data = [];
        foreach ($mediaConfig[$this->fieldName]['collections'] ?? [] as $collection => $mediaData) {
            $mediaItems = $this->item->getMedia($collection);

            $data['mediaCollections'][$collection]['title'] = $mediaData['title'] ?? '';
            $data['mediaCollections'][$collection]['media'] = $mediaItems->toArray();
            $data['mediaCollections'][$collection]['customProperties'] = $mediaData['customProperties'] ?? [];
            $data['mediaCollections'][$collection]['editor'] = (isset($mediaData['editor']) && ! empty($mediaData['editor'])) ? $mediaData['editor'] : new stdClass;
            $data['mediaCollections'][$collection]['disks'] = (isset($mediaData['disks']) && ! empty($mediaData['disks'])) ? $mediaData['disks'] : new stdClass;
            $data['mediaCollections'][$collection]['uploaderOptions'] = (isset($mediaData['uploaderOptions']) && ! empty($mediaData['uploaderOptions'])) ? $mediaData['uploaderOptions'] : new stdClass;
        }

        return $data;
    }
}
