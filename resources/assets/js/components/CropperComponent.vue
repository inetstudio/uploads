<template>
    <div id="crop_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title"></h4>
                    <small class="font-bold">Выберите область изображения</small>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="label label-warning description"></small>
                            <p class="m-t-lg">Размер выбранной области: <span class="label crop-size"></span></p>

                            <div class="m-b-xs">
                                <img src="" class="m-b-md img-responsive center-block" ref="image">
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Переместить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить">
                                      <span class="fa fa-arrows"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Выбрать область" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Выбрать область">
                                      <span class="fa fa-crop"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Увеличить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                                      <span class="fa fa-search-plus"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Уменьшить" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                                      <span class="fa fa-search-minus"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Переместить влево" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить влево">
                                      <span class="fa fa-arrow-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Переместить вправо" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вправо">
                                      <span class="fa fa-arrow-right"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Переместить вверх" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вверх">
                                      <span class="fa fa-arrow-up"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Переместить вниз" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вниз">
                                      <span class="fa fa-arrow-down"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Повернуть влево" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть влево">
                                      <span class="fa fa-rotate-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Повернуть вправо" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть вправо">
                                      <span class="fa fa-rotate-right"></span>
                                    </span>
                                </button>
                            </div>

                            <div class="btn-group m-b-xs" style="margin: 10px 10px 0 0;">
                                <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Отразить горизонтально" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить горизонтально">
                                      <span class="fa fa-arrows-h"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Отразить вертикально" style="margin-right: 2px;">
                                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить вертикально">
                                      <span class="fa fa-arrows-v"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="img-preview" ref="preview">
                                <img src="" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'vue-cropper',
        props: {

        },
        mounted() {
            let component = this;

            let cropperOptions = {
                viewMode: 2,
                preview: component.$refs.preview,
                aspectRatio: component.options.ratio,
                data: component.options.data
            };

            $(component.$refs.image).on({
                crop: function (e) {
                    let width = Math.round(e.width);
                    let height = Math.round(e.height);

                    //infoContainer.removeClass('label-primary').removeClass('label-danger');

                    switch (component.crop.type) {
                        case 'min':
                            if (width < component.crop.width || height < component.crop.height) {
                                //infoContainer.addClass('label-danger');
                            } else {
                                //infoContainer.addClass('label-primary');
                            }
                            break;
                        case 'fixed':
                            if (width !== component.crop.width && height !== component.crop.height) {
                                //infoContainer.addClass('label-danger');
                            } else {
                                //infoContainer.addClass('label-primary');
                            }
                            break;
                    }

                    //infoContainer.text(width + 'x' + height);
                }
            }).cropper(cropperOptions);
        },
        data() {
            return {
                crop: {
                    type: '',
                    width: 0,
                    height: 0
                },
                options: {
                    data: '',
                    ratio: 1
                },
            };
        }
    }
</script>

<style scoped>

</style>