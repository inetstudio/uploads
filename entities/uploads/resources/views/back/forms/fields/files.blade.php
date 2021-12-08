@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $media = (old('media.'.$transformName))
        ? ((array) json_decode(old('media.'.$transformName)))
        : $value->map(function ($mediaItem) {
            return [
                'id' => $value->id ?? 0,
                'serverId' => '',
                'collection_name' => $attributes['config']['collection_name'] ?? ($value->collection_name ?? $transformName),
                'name' => $value->name ?? '',
                'file_name' => $value->file_name ?? '',
                'mime_type' => $value->mime_type ?? '',
                'disk' =>  $attributes['config']['disks']['disk'] ?? ($value->disk ?? ''),
                'conversions_disk' => $attributes['config']['disks']['conversions_disk'] ?? ($value->conversions_disk ?? ''),
                'manipulations' => $value->manipulations ?? new \stdClass,
                'custom_properties' => $value->custom_properties ?? new \stdClass,
            ];
        })->toArray();
@endphp

<div class="uploader">
    <files-uploader
        :name-prop="'{{ $transformName }}'"
        :label-prop="'{{ $attributes['label']['title'] }}'"
        :media-prop="{{ json_encode($media) }}"
    ></files-uploader>
</div>
