<template>
    <div>
      <div class="row m-t-lg">
          <div class="col-md-12">
              <small class="label label-warning description">{{ (crop.size) ? crop.size.description : '' }}</small>
              <p class="m-t-lg">Размер выбранной области: <span class="label crop-size" :class="selectedSizeWarning">{{ selectedSize }}</span></p>

              <div class="m-b-xs">
                  <img class="m-b-md img-fluid center-block" ref="image">
              </div>

              <div class="btn-group m-b-xs edit-group">
                <button type="button" class="btn btn-default edit-button" data-method="setDragMode" data-option="move" title="Переместить" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить">
                    <span class="fa fa-arrows-alt"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="setDragMode" data-option="crop" title="Выбрать область" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Выбрать область">
                    <span class="fa fa-crop"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="zoom" data-option="0.1" title="Увеличить" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                    <span class="fa fa-search-plus"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="zoom" data-option="-0.1" title="Уменьшить" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                    <span class="fa fa-search-minus"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="move" data-option="-10" data-second-option="0" title="Переместить влево" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить влево">
                    <span class="fa fa-arrow-left"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="move" data-option="10" data-second-option="0" title="Переместить вправо" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вправо">
                    <span class="fa fa-arrow-right"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="move" data-option="0" data-second-option="-10" title="Переместить вверх" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вверх">
                    <span class="fa fa-arrow-up"></span>
                  </span>
                </button>

                <button type="button" class="btn btn-default edit-button" data-method="move" data-option="0" data-second-option="10" title="Переместить вниз" @click.stop.prevent="edit">
                  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вниз">
                    <span class="fa fa-arrow-down"></span>
                  </span>
                </button>
              </div>
          </div>
      </div>
      <div class="row m-t-lg">
        <div class="col-md-12">
          <div class="img-preview" ref="preview_wrapper" style="width: 384px; height: 512px">
            <img ref="preview" />
          </div>
        </div>
      </div>
      <div class="row m-t-lg">
        <div class="col-md-12">
          <a href="#" class="btn btn-primary save" @click.prevent="saveCrop">Подтвердить</a>
          <p class="text-danger m-t-xs" v-if="result.error">Выбрана некорректная область изображения</p>
          <p class="text-success m-t-xs" v-if="result.success">Изменения сохранены</p>
        </div>
      </div>
    </div>
</template>

<script>
  import Cropper from 'cropperjs';

  export default {
    name: 'Crop',
    props: {
      cropProp: {
        type: Object,
        default() {
          return {
            name: '',
            size: {
              description: '',
              height: 0,
              type: '',
              width: 0
            },
            title: '',
            value: {
              width: 0,
              height: 0,
              x: 0,
              y: 0,
              rotate: 0,
              scaleX: 1,
              scaleY: 1
            }
          };
        }
      },
      fileItemProp: {
        type: Object,
        required: true
      }
    },
    data() {
        return {
          cropper: undefined,
          crop: _.cloneDeep(this.cropProp),
          result: {
            warning: false,
            error: false,
            success: false,
            width: 0,
            height: 0
          }
        };
    },
    computed: {
        selectedSize() {
          let component = this;

          return component.result.width + 'x' + component.result.height;
        },
        selectedSizeWarning() {
          let component = this;

          return (component.result.warning) ? 'label-danger' : 'label-primary';
        }
    },
    watch: {
      'cropProp': {
        handler: function (newValue, oldValue) {
          let component = this;

          if (newValue.name === oldValue.name) {
            return;
          }

          component.crop = _.cloneDeep(component.cropProp);

          component.initCropper();
        },
        deep: true
      }
    },
      methods: {
        initCropper() {
          let component = this;

          if (typeof component.cropper !== 'undefined') {
            component.cropper.destroy();
          }

          let imageUrl = URL.createObjectURL(component.fileItemProp.file);
          component.$refs.image.src = imageUrl;
          component.$refs.preview.src = imageUrl;

          let cropWidth = component.crop.size.width;
          let cropHeight = component.crop.size.height;

          let cropperOptions = {
            viewMode: 2,
            preview: component.$refs.preview_wrapper,
            aspectRatio: (cropHeight === 0) ? 0 : cropWidth / cropHeight,
            data: (component.crop.value.width === 0) ? null : component.crop.value,
            autoCrop: true,
            crop(event) {
              let width = Math.round(event.detail.width);
              let height = Math.round(event.detail.height);

              switch (component.crop.size.type) {
                case 'min':
                  component.result.warning = (width < cropWidth || height < cropHeight);
                  break;
                case 'fixed':
                  component.result.warning = (width !== cropWidth && height !== cropHeight);
                  break;
              }

              component.result.width = width;
              component.result.height = height;
              component.result.error = false;
              component.result.success = false;
            }
          };

          component.cropper = new Cropper(component.$refs.image, cropperOptions);
        },
        edit(event) {
            let component = this;

            let element = event.currentTarget,
                method = element.getAttribute('data-method'),
                option = element.getAttribute('data-option'),
                secondOption = element.getAttribute('data-second-option');

            component.cropper[method](option, secondOption);
        },
        saveCrop() {
          let component = this;

          if (component.result.warning) {
            component.result.error = true;

            return;
          }

          component.$emit('update:crop', {
            name: component.crop.name,
            value: _.mapValues(component.cropper.getData(), (v) => { return Math.round(v);}),
          });

          component.result.success = true;
        }
      },
      mounted() {
        let component = this;

        component.initCropper();
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
