<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label font-bold">{{ label }}</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="ibox">

                            <progress-bar :progress-prop="progress"></progress-bar>

                            <div class="ibox-content no-borders" :class="{'sk-loading': progress.state}">
                                <div class="sk-spinner sk-spinner-double-bounce">
                                    <div class="sk-double-bounce1"></div>
                                    <div class="sk-double-bounce2"></div>
                                </div>

                                <img :src="image.src.file.path" :data-src="image.src.file.path || 'holder.js/100px200?auto=yes&font=\'Font Awesome 5 Free\'&text='" :alt="imageAlt" class="m-b-md img-fluid" :data-holder-rendered="image.src.file.path ? false : ''" ref="preview">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="btn btn-success upload-btn" ref="upload">
                            <i class="fa fa-upload m-r-xs"></i>Загрузить изображение
                        </a>

                        <div class="btn-group" v-show="image.src.file.path">
                            <div class="crop_buttons m-t-lg">
                                <a v-for="crop in image.crops" href="#" class="btn m-b-xs btn-w-m" :class="(! crop.value) ? 'btn-default' : 'btn-primary'" @click.prevent="startCrop(crop)"><i class="fa fa-crop m-r-xs"></i>{{ crop.title }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="image.src.file.path">
            <base-input-text
                v-for="(item, index) in image.properties"
                :key="index"
                :label="item.title"
                :name="name + '[' + item.name + ']'"
                :value.sync="item.value"
                :showHr="false"
            />
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default {
        name: 'image-uploader',
        props: {
            name: {
                type: String,
                required: true
            },
            label: {
                type: String,
                default: ''
            },
            imageProp: {
              type: Object,
              default() {
                return {
                  src: {
                    temp: {
                      path: '',
                      name: ''
                    },
                    file: {
                      path: '',
                      name: ''
                    }
                  },
                  properties: [],
                  crops: []
                };
              }
            }
        },
        data() {
            return {
              uploader: undefined,
              progress: {
                state: false,
                percents: 0,
                text: '',
                style: {
                  width: '0%'
                }
              },
              image: _.cloneDeep(this.imageProp),
            }
        },
        computed: {
          imageAlt() {
            let component = this;

            let alt = '';

            component.image.properties.forEach(property => {
                if (property.name === 'alt') {
                  alt = property.value;
                }
            });

            return alt;
          }
        },
        watch: {
          'image': {
            handler: function(newValue) {
              let component = this;

              component.$emit('update:image', {
                name: component.name,
                image: newValue
              });
            },
            deep: true
          },
          'image.src.file.path': function(newPath) {
            let component = this;

            if (! newPath) {
              component.$nextTick(function() {
                component.generatePreviewPlaceholder();
              });
            }
          },
          imageProp: function(newValues) {
              let component = this;

              if (window.hash(component.image) !== window.hash(newValues)) {
                component.image = _.cloneDeep(newValues);
              }
          }
        },
        methods: {
            generatePreviewPlaceholder() {
              let component = this;

              window.Holder.run({
                images: component.$refs.preview
              });
            },
            startCrop(crop) {
              window.Admin.vue.helpers.initComponent('uploads_package', 'cropper', {});

              window.Admin.stores['uploads'].commit('setSrc', this.image.src.file.path);
              window.Admin.stores['uploads'].commit('setCrop', crop);

              window.waitForElement('#crop_modal', function () {
                $('#crop_modal').modal();
              });
            }
        },
        mounted() {
            let component = this;

            component.generatePreviewPlaceholder();

            let uploader = new plupload.Uploader({
                browse_button: component.$refs.upload,
                url: route('back.upload'),
                filters: {
                    mime_types: "image/*"
                },
                chunk_size: '500kb',
                multi_selection: false,
                file_data_name: component.name + '[image]',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                multipart_params: {
                    fieldName: component.name + '[image]'
                }
            });

            uploader.init();

            uploader.bind('FilesAdded', function (up) {
                component.progress.state = true;
                up.start();
            });

            uploader.bind('UploadProgress', function (up) {
                component.progress.percents = up.total.percent;
                component.progress.text = up.total.percent + '% (' + (up.total.uploaded + 1) + ' из ' + up.files.length + ')';
                component.progress.style.width = up.total.percent + '%';
            });

            uploader.bind('FileUploaded', function (up, file, response) {
                response = JSON.parse(response.response);

                component.image.src.temp.path = response.result.tempPath;
                component.image.src.temp.name = response.result.tempName;
                component.image.src.file.path = response.result.tempPath;
                component.image.src.file.name = file.name;

                component.progress.state = false;
                component.progress.percents = 0;
                component.progress.text = '';
                component.progress.style.width = '0%';

                component.image.properties.forEach(function(item) {
                    item.value = '';
                });

                component.image.crops.forEach(function(item) {
                    item.value = '';
                });

                up.splice();
            });

            component.uploader = uploader;
        }
    }
</script>

<style scoped>
</style>
