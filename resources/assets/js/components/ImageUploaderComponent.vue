<template>
    <div class="form-group">
        <input :name="fieldName + '[temppath]'" type="hidden" :value="src.temp.path">
        <input :name="fieldName + '[tempname]'" type="hidden" :value="src.temp.name">
        <input :name="fieldName + '[filepath]'" type="hidden" :value="src.file.path">
        <input :name="fieldName + '[filename]'" type="hidden" :value="src.file.name">

        <label class="col-sm-2 control-label">{{ label }}</label>
        <div class="col-sm-10">
            <div class="col-md-6">
                <div class="ibox">

                    <vue-progress-bar :progress="progress"></vue-progress-bar>

                    <div class="ibox-content" :class="{'sk-loading': progress.state}">
                        <div class="sk-spinner sk-spinner-double-bounce">
                            <div class="sk-double-bounce1"></div>
                            <div class="sk-double-bounce2"></div>
                        </div>
                        <div class="preview">
                            <img :src="src.temp.path" :data-src="src.temp.path ? '' : 'holder.js/100px200?auto=yes&font=FontAwesome&text=не загружено'" class="m-b-md img-responsive placeholder">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="btn-group">
                    <a href="#" class="btn btn-success upload-btn" ref="upload">
                        <i class="fa fa-upload m-r-xs" style="margin-right: 10px;"></i>Загрузить изображение
                    </a><br/>

                    <div class="crop_buttons m-t-lg">
                        <div v-for="item in crops.variants">
                            <a href="#" style="display: block;" class="btn m-b-xs btn-w-m start-cropper"><i class="fa fa-crop"></i>{{ item.title }}</a>

                            <input :name="fieldName + '[crop][' + item.name + ']'" type="hidden" :value="item.value">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="properties.state">
            <div class="form-group" v-for="item in properties.fields">
                <label :for="item.field" class="col-sm-2 control-label">{{ item.title }}</label>
                <div class="col-sm-10">
                    <input v-model="item.value" class="form-control" :name="fieldName + '[' + item.name + ']'" type="text">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'vue-image-uploader',
        props: {
            fieldName: {
                type: String,
                required: true
            },
            label: {
                type: String,
                default: ''
            },
            propertiesFields: {
                type: Array,
                default() {
                    return [];
                }
            },
            cropsVariants: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            let component = this;

            let uploader = new plupload.Uploader({
                browse_button: component.$refs.upload,
                url: route('back.upload'),
                filters: {
                    mime_types: "image/*"
                },
                chunk_size: '500kb',
                multi_selection: false,
                file_data_name: component.fieldName + '[image]',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                multipart_params: {
                    fieldName: component.fieldName + '[image]'
                }
            });

            uploader.init();

            uploader.bind('FilesAdded', function (up) {
                component.properties.state = false;
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

                component.src.temp.path = response.result.tempPath;
                component.src.temp.name = response.result.tempName;
                component.src.file.path = response.result.tempPath;
                component.src.file.name = file.name;

                component.progress.state = false;
                component.progress.percents = 0;
                component.progress.text = '';
                component.progress.style.width = '0%';

                component.properties.fields.forEach(function(item) {
                    item.value = '';
                });

                component.properties.state = true;
            });

            component.uploader = uploader;
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
                crops: {
                    state: false,
                    variants: this.cropsVariants
                },
                properties: {
                    state: false,
                    fields: this.propertiesFields
                },
                src: {
                    temp: {
                        path: '',
                        name: ''
                    },
                    file: {
                        path: '',
                        name: ''
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
