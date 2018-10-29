if ($('#uploader_modal').length > 0) {
    window.Admin.modals.uploaderModal = new Vue({
        el: '#uploader_modal',
        data: {
            uploader: undefined,
            target: '',
            upload: true,
            progress: {
                state: false,
                percents: 0,
                text: '',
                style: {
                    width: '0%'
                }
            },
            images: [],
            inputs: []
        },
        methods: {
            save: function (event) {
                let target = this.target;

                $.each(this.images, function (key, image) {
                    Admin.containers.images[target].images.push(image);
                });

                $('#uploader_modal').modal('hide');

                this.images.splice(0);
                this.upload = true;
            }
        }
    });
}

if ($('#edit_image_modal').length > 0) {
    window.Admin.modals.imageEditModal = new Vue({
        el: '#edit_image_modal',
        data: {
            target: '',
            image: {},
            inputs: []
        },
        methods: {
            save: function () {
                let image = this.image;

                $('#edit_image_modal input').each(function () {
                    image.properties[$(this).attr('name')] = $(this).val();
                });

                $('#edit_image_modal').modal('hide');
            }
        }
    });
}

if ($('#gallery_images').length > 0) {
    Admin.containers.images['gallery'] = new Vue({
        el: '#gallery_images',
        data: {
            editor: undefined,
            widget: 0,
            target: 'gallery',
            images: [],
            inputs: JSON.parse($('#gallery_images').attr('data-properties'))
        },
        methods: {
            edit: function (index) {
                let modalWindow = $('#edit_image_modal');

                Admin.modals.imageEditModal.target = this.target;
                Admin.modals.imageEditModal.image = this.images[index];
                Admin.modals.imageEditModal.inputs = this.inputs;

                modalWindow.modal();
            },
            remove: function (index) {
                this.$delete(this.images, index);
            }
        }
    });

    $('#gallery_modal .add-images').on('click', function (event) {
        event.preventDefault();

        initUploader('gallery');

        $('#uploader_modal').modal();
    });

    $('#gallery_modal .save').on('click', function (event) {
        event.preventDefault();

        if (Admin.containers.images['gallery'].images.length === 0) {
            $('#gallery_modal').modal('hide');

            return false;
        }

        $('#gallery_modal').find('.ibox-content').toggleClass('sk-loading');

        window.Admin.modules.widgets.saveWidget(Admin.containers.images['gallery'].widget, {
            view: 'admin.module.widgets::front.partials.content.gallery_widget',
            params: {},
            additional_info: {
                material_type: 'gallery_widget'
            }
        }, {
            editor: Admin.containers.images['gallery'].editor,
            type: 'gallery',
            alt: 'Виджет-галерея'
        }, function (widget) {
            let form = $('#gallery_modal form'),
                data = form.serializeJSON();

            data.widgetID = widget.id;
            data.material_type = 'gallery_widget';

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function () {
                    $('#gallery_modal').find('.ibox-content').toggleClass('sk-loading');

                    $('#gallery_modal').modal('hide');
                },
                error: function () {
                    $('#gallery_modal').find('.ibox-content').toggleClass('sk-loading');

                    swal({
                        title: "Ошибка",
                        text: "При сохранении виджета произошли ошибки",
                        type: "error"
                    });
                }
            });
        });
    });
}

tinymce.PluginManager.add('images', function(editor) {
    if ($(editor.getElement()).get(0).hasAttribute('hasImages')) {
        let name = editor.id,
            container = $('#' + name + '_images'),
            images = JSON.parse(container.attr('data-media'));

        Admin.containers.images[name] = new Vue({
            el: '#' + name + '_images',
            data: {
                target: name,
                images: images,
                inputs: JSON.parse(container.attr('data-properties'))
            },
            methods: {
                add: function (index) {
                    let alt = (this.images[index].properties.alt) ? this.images[index].properties.alt : '',
                        description = (this.images[index].properties.description) ? this.images[index].properties.description : '',
                        copyright = (this.images[index].properties.copyright) ? ' &copy; ' + this.images[index].properties.copyright : '',
                        tpl = _.unescape($('.content-image-template').first().html());

                    let imageBlock = _.unescape(_.template(tpl)({
                        src: this.images[index].src,
                        alt: alt,
                        description: description,
                        copyright: copyright
                    }));

                    tinymce.get(this.target).editorManager.execCommand('mceInsertContent', false, imageBlock);
                },
                edit: function (index) {
                    let modalWindow = $('#edit_image_modal');

                    Admin.modals.imageEditModal.target = this.target;
                    Admin.modals.imageEditModal.image = this.images[index];
                    Admin.modals.imageEditModal.inputs = this.inputs;

                    modalWindow.modal();
                },
                remove: function (index) {
                    this.$delete(this.images, index);
                }
            }
        });

        editor.addButton('images', {
            title: 'Загрузить изображения',
            icon: 'image',
            onclick: function () {
                initUploader(name);

                $('#uploader_modal').modal();
            }
        });

        editor.addButton('gallery', {
            title: 'Добавить галерею',
            image: '/admin/images/tinymce-button-gallery-widget.png',
            onclick: function () {
                Admin.containers.images['gallery'].images = [];

                let content = editor.selection.getContent();

                Admin.containers.images['gallery'].editor = editor;

                if (content !== '' && ! /<img class="content-widget".+data-type="gallery".+\/>/g.test(content)) {
                    swal({
                        title: "Ошибка",
                        text: "Необходимо выбрать виджет-галерею",
                        type: "error"
                    });

                    return false;
                } else if (content !== '') {
                    $('#gallery_modal').find('.ibox-content').toggleClass('sk-loading');

                    Admin.containers.images['gallery'].widget = $(content).attr('data-id');

                    window.Admin.modules.widgets.getWidget(Admin.containers.images['gallery'].widget, function (widget) {
                        $.ajax({
                            url: route('back.widgets.gallery.get'),
                            method: 'GET',
                            data: {
                                widgetID: widget.id,
                                collection: 'gallery'
                            },
                            dataType: 'json',
                            success: function (data) {
                                Admin.containers.images['gallery'].images = data;

                                $('#gallery_modal').find('.ibox-content').toggleClass('sk-loading');
                            }
                        });
                    });
                } else {
                    Admin.containers.images['gallery'].widget = 0;
                }

                $('#gallery_modal').modal();
            }
        });
    }
});

function initUploader(field) {
    let $input = $('#uploader-area'),
        url = $input.attr('data-target');

    Admin.modals.uploaderModal.images.splice(0);
    Admin.modals.uploaderModal.upload = true;
    Admin.modals.uploaderModal.target = field;
    Admin.modals.uploaderModal.inputs = JSON.parse($('#'+field+'_images').attr('data-properties'));

    if (typeof Admin.modals.uploaderModal.uploader === 'undefined') {
        Admin.modals.uploaderModal.uploader = new plupload.Uploader({
            browse_button: 'uploader-area',
            drop_element: 'uploader-area',
            url: url,
            filters: {
                mime_types: "image/gif,image/jpeg,image/pjpeg,image/png"
            },
            chunk_size: '500kb',
            multi_selection: true,
            file_data_name: field,
            multipart_params: {
                fieldName: field
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        Admin.modals.uploaderModal.uploader.init();

        Admin.modals.uploaderModal.uploader.bind('FilesAdded', function (up) {
            Admin.modals.uploaderModal.progress.state = true;
            Admin.modals.uploaderModal.upload = false;

            up.start();
        });

        Admin.modals.uploaderModal.uploader.bind('UploadProgress', function (up) {
            Admin.modals.uploaderModal.progress.percents = up.total.percent;
            Admin.modals.uploaderModal.progress.text = up.total.percent + '% (' + (up.total.uploaded + 1) + ' из ' + up.files.length + ')';
            Admin.modals.uploaderModal.progress.style.width = up.total.percent + '%';
        });

        Admin.modals.uploaderModal.uploader.bind('FileUploaded', function (up, file, response) {
            response = JSON.parse(response.response);

            let properties = {};
            $.each(Admin.modals.uploaderModal.inputs, function (key, value) {
                properties[value.name] = '';
            });

            Admin.modals.uploaderModal.images.push({
                src: response.result.tempPath,
                thumb: response.result.tempPath,
                tempname: response.result.tempName,
                name: file.name.substring(0, file.name.lastIndexOf(".")),
                filename: file.name,
                properties: properties
            });
        });

        Admin.modals.uploaderModal.uploader.bind('UploadComplete', function (up) {
            up.splice();

            Admin.modals.uploaderModal.progress.state = false;
            Admin.modals.uploaderModal.progress.percents = 0;
            Admin.modals.uploaderModal.progress.text = '';
            Admin.modals.uploaderModal.progress.style.width = '0%';
        });
    } else {
        Admin.modals.uploaderModal.uploader.setOption('file_data_name', field);
        Admin.modals.uploaderModal.uploader.setOption('multipart_params', {
            fieldName: field
        });
    }
}
