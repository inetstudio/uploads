<template>
    <div id="crop_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title">{{currentCrop.title || '' }}</h4>
                    <small class="font-bold">Выберите область изображения</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="label label-warning description">{{ (currentCrop.size) ? currentCrop.size.description : '' }}</small>
                            <p class="m-t-lg">Размер выбранной области: <span class="label crop-size" :class="selectedSizeWarning">{{ selectedSize }}</span></p>

                            <div class="m-b-xs">
                                <img :src="src" class="m-b-md img-fluid center-block" ref="image">
                            </div>

                            <div class="btn-group m-b-xs edit-group">
                                <button type="button" class="btn btn-primary edit-button" data-method="setDragMode" data-option="move" title="Переместить" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить">
                                      <span class="fa fa-arrows-alt"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary edit-button" data-method="setDragMode" data-option="crop" title="Выбрать область" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Выбрать область">
                                      <span class="fa fa-crop"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs edit-group">
                                <button type="button" class="btn btn-primary edit-button" data-method="zoom" data-option="0.1" title="Увеличить" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                                      <span class="fa fa-search-plus"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary edit-button" data-method="zoom" data-option="-0.1" title="Уменьшить" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                                      <span class="fa fa-search-minus"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs edit-group">
                                <button type="button" class="btn btn-primary edit-button" data-method="move" data-option="-10" data-second-option="0" title="Переместить влево" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить влево">
                                      <span class="fa fa-arrow-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary edit-button" data-method="move" data-option="10" data-second-option="0" title="Переместить вправо" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вправо">
                                      <span class="fa fa-arrow-right"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary edit-button" data-method="move" data-option="0" data-second-option="-10" title="Переместить вверх" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вверх">
                                      <span class="fa fa-arrow-up"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary edit-button" data-method="move" data-option="0" data-second-option="10" title="Переместить вниз" @click.stop.prevent="edit">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вниз">
                                      <span class="fa fa-arrow-down"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="img-preview" ref="preview">
                                <img :src="src" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary save" @click="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'cropper',
        data() {
            return {
                result: {
                    warning: false,
                    width: 0,
                    height: 0
                }
            };
        },
        computed: {
            selectedSize() {
                return this.result.width + 'x' + this.result.height;
            },
            selectedSizeWarning() {
                return (this.result.warning) ? 'label-danger' : 'label-primary';
            },
            src() {
                return window.Admin.stores['uploads'].state.src;
            },
            currentCrop() {
                return window.Admin.stores['uploads'].state.crop;
            }
        },
        mounted() {
            let component = this;

            $('#crop_modal').on('shown.bs.modal', function () {
                component.initCropper();
            });

            $('#crop_modal').on('hidden.bs.modal', function () {
                $(component.$refs.image).cropper('destroy');
            });
        },
        methods: {
            initCropper() {
                let component = this;

                if (Object.keys(component.currentCrop).length !== 0) {
                    let cropWidth = component.currentCrop.size.width;
                    let cropHeight = component.currentCrop.size.height;

                    let cropperOptions = {
                        viewMode: 2,
                        preview: component.$refs.preview,
                        aspectRatio: (component.currentCrop.size.height === 0) ? 0 : cropWidth / cropHeight,
                        data: (component.currentCrop.value === '') ? null : JSON.parse(component.currentCrop.value)
                    };

                    $(component.$refs.image).on({
                        crop: function (e) {
                            let width = Math.round(e.detail.width);
                            let height = Math.round(e.detail.height);

                            switch (component.currentCrop.size.type) {
                                case 'min':
                                    component.result.warning = (width < cropWidth || height < cropHeight);
                                    break;
                                case 'fixed':
                                    component.result.warning = (width !== cropWidth && height !== cropHeight);
                                    break;
                            }

                            component.result.width = width;
                            component.result.height = height;
                        }
                    }).cropper(cropperOptions);
                }
            },
            edit(event) {
                let component = this;

                let $this = $(event.currentTarget),
                    $image = $(component.$refs.image),
                    data = $this.data();

                if ($image.data('cropper') && data.method) {
                    $image.cropper(data.method, data.option, data.secondOption);
                }
            },
            save() {
              let component = this;

              if (! component.result.warning) {
                window.Admin.stores['uploads'].commit('setCropData', JSON.stringify($(component.$refs.image).cropper('getData')));
              }

              $('#crop_modal').modal('hide');
            }
        }
    }
</script>

<style scoped>
    .edit-group {
        margin: 10px 10px 0 0;
    }
    .edit-button {
        margin-right: 2px;
    }
</style>
