@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $media = (old('media.'.$transformName))
        ? ((array) json_decode(old('media.'.$transformName), true))
        : [[
            'id' => $value->id ?? 0,
            'serverId' => '',
            'collection_name' => $attributes['config']['collection_name'] ?? ($value->collection_name ?? $transformName),
            'name' => $value->name ?? '',
            'file_name' => $value->file_name ?? '',
            'mime_type' => $value->mime_type ?? '',
            'disk' =>  $attributes['config']['disks']['disk'] ?? ($value->disk ?? ''),
            'conversions_disk' => $attributes['config']['disks']['conversions_disk'] ?? ($value->conversions_disk ?? ''),
            'manipulations' => (! $value || empty($value->manipulations)) ? new \stdClass : $value->manipulations,
            'custom_properties' => $value->custom_properties ?? new \stdClass,
        ]];
@endphp

<div class="vue-media-uploader">
    <media-uploader
        :name-prop="'{{ $transformName }}'"
        :label-prop="'{{ $attributes['label']['title'] }}'"
        :media-prop="{{ json_encode($media) }}"
        :editor-prop="{{ json_encode($attributes['config']['editor'] ?? []) }}"
        :custom-properties-prop="{{ json_encode($attributes['config']['custom_properties'] ?? []) }}"
        :uploader-options="{
            acceptedFileTypes: ['image/*'],
            allowMultiple: true
        }"
    ></media-uploader>
</div>
