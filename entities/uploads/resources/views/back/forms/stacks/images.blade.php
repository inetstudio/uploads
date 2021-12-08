@php
    /*
    $fieldID = $value;
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $media = [];

    if (old($transformName.'.images')) {
        $media = old($transformName.'.images');
        foreach ($media as &$img) {
            if ($img['tempname']) {
                $img['thumb'] = $img['src'];
            } elseif ($attributes['media']) {
                $mediaItem = $attributes['media']->where('id', $img['id'])->first();
                $collection = $mediaItem->collection_name;
                $img['thumb'] = ($mediaItem && $mediaItem->getUrl($collection.'_admin')) ? url($mediaItem->getUrl($collection.'_admin')) : url($mediaItem->getUrl());
            }
        }
    } else {
        foreach ($attributes['media'] as $mediaItem) {
            $collection = $mediaItem->collection_name;
            $data = [
                'id' => $mediaItem->id,
                'src' => url($mediaItem->getUrl()),
                'name' => $mediaItem->name,
                'thumb' => ($mediaItem->getUrl($collection.'_admin')) ? url($mediaItem->getUrl($collection.'_admin')) : url($mediaItem->getUrl()),
                'properties' => $mediaItem->custom_properties,
            ];

            $media[] = $data;
        }
    }

    // Поля изображений, доступные для редактирования
    $properties = (isset($attributes['fields'])) ? $attributes['fields'] : [];
    $properties = json_encode($properties);
 */
@endphp

{{--
<div class="row" id="{{ $fieldID }}_images" data-media="{{ json_encode($media) }}" data-properties="{{ $properties }}">
    <input name="{{ $name }}[has_images]" type="hidden" value="1">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <div class="file-box" style="width: 140px" v-for="(image, index) in images">
            <div class="file">
                <span class="corner"></span>
                <div class="image">
                    <img :src="image.thumb" class="img-fluid">

                    <input :name="'{{ $name }}[images][' + index + '][id]'" type="hidden" :value="image.id">
                    <input :name="'{{ $name }}[images][' + index + '][src]'" type="hidden" :value="image.src">
                    <input :name="'{{ $name }}[images][' + index + '][tempname]'" type="hidden" :value="image.tempname">
                    <input :name="'{{ $name }}[images][' + index + '][filename]'" type="hidden" :value="image.filename">
                    <input v-for="(value, key) in image.properties" :name="'{{ $name }}[images][' + index + '][properties][' + key + ']'" type="hidden" :value="value">
                </div>
                <div class="file-name">
                    @if (in_array('add', $attributes['controls']))
                        <a href="#" class="btn btn-primary btn-xs add" @click.prevent="add(index)">
                            <i class="fa fa-plus"></i>
                        </a>
                    @endif
                    @if (in_array('edit', $attributes['controls']))
                        <a href="#" class="btn btn-white btn-xs edit" @click.prevent="edit(index)">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                    @endif
                    @if (in_array('remove', $attributes['controls']))
                        <a href="#" class="btn btn-danger btn-xs delete" @click.prevent="remove(index)">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.module.uploads::back.partials.content_image')

@pushonce('modals:uploader')
    <div class="modal inmodal fade" id="uploader_modal" tabindex="-1" role="dialog" aria-hidden="true" ref="vuemodal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title">Загрузка изображений</h4>
                </div>

                <div class="modal-body">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div v-show="progress.state" class="progress progress-bar-default">
                                    <div :style="progress.style" aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progress.percents" role="progressbar" class="progress-bar">
                                        <span>@{{ progress.text }}</span>
                                    </div>
                                </div>
                                <div v-show="upload" id="uploader-area" data-target="{{ route('back.upload') }}">Перенесите изображения в область</div>
                            </div>
                        </div>
                        <template v-for="image in images">
                            <div class="row upload-image m-t-md" :data-hash="image.hash">
                                <div class="col-md-3">
                                    <img :src="image.src" class="m-b-md img-fluid placeholder" :data-tempname="image.tempname" :data-filename="image.filename">
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 col-form-label font-bold">Имя файла</label>
                                        <div class="col-sm-10"><input name="image_name[]" type="text" class="form-control" :value="image.name" disabled="disabled"></div>
                                    </div>
                                    <div class="form-group row" v-for="input in inputs">
                                        <label :for="input.name" class="col-sm-2 col-form-label font-bold">@{{ input.title }}</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" v-model="image.properties[input.name]" :name="input.name" type="text" value="" :id="input.name">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </template>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce

@pushonce('modals:edit_image')
    <div class="modal inmodal fade" id="edit_image_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title">Редактирование изображения</h4>
                </div>

                <div class="modal-body">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label font-bold">Имя файла</label>
                                <div class="col-sm-10"><input name="edit_image_name" type="text" class="form-control" :value="image.name" disabled="disabled"></div>
                            </div>
                            <div class="row m-b-md">
                                <img :src="image.src" class="img-fluid" style="max-height: 400px; display: block; margin: auto" />
                                <div class="hr-line-dashed"></div>
                            </div>
                            <template v-for="input in inputs">
                                <div class="form-group row">
                                    <label :for="input.name" class="col-sm-2 col-form-label font-bold">@{{ input.title }}</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" :name="input.name" type="text" :value="image.properties[input.name]" :id="input.name">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce
--}}
