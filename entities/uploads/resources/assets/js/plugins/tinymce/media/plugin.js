import Swal from 'sweetalert2';

import ModalMediaUploader from '../../../components/ModalMediaUploader';

if ($('#gallery_images').length > 0) {
    Admin.containers.images['gallery'] = new window.Vue({
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

            data.widgetId = widget.id;
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

                    Swal.fire({
                        title: "Ошибка",
                        text: "При сохранении виджета произошли ошибки",
                        icon: "error"
                    });
                }
            });
        });
    });
}

tinymce.PluginManager.add('media', (editor) => {
    editor.addButton('media', {
        title: 'Медиа',
        icon: 'fa fa-photo-video',
        onclick: () => {
            editor.fire('ModalVueComponent', {
                modalOptions: {
                    component: ModalMediaUploader,
                    component_properties: {
                        save: (result) => {
                            for (const collectionName in result) {
                                if (result.hasOwnProperty(collectionName)) {
                                    component.$set(component.media[collectionName], 'media', result[collectionName].media);
                                }
                            }
                        }
                    },
                    modal_properties: {
                        height: 'auto',
                        scrollable: true,
                    },
                    modal_events: {}
                }
            });
        }
    });
    /*

        editor.addButton('gallery', {
            title: 'Добавить галерею',
            icon: 'fa fa-images',
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
                                widgetId: widget.id,
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
     */
});
