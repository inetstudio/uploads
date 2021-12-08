<div class="vue-media-field">
    <media-field
        :name="'{{ $fieldName }}'"
        :label="'{{ $label }}'"
        :media-collections="{{ json_encode($data['mediaCollections']) }}"
    ></media-field>
</div>
