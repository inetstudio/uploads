$(document).ready(function () {
    initImageUploaders($(document));
});

window.initImageUploaders = function (container) {
    if (container.find('.upload-btn').length > 0) {
        container.find('.upload-btn').each(function () {
            let $input = $(this),
                wrapper = $(this).closest('.image_upload'),
                url = $input.attr('data-target'),
                field = $input.attr('data-field'),

                name = field + '[file]',
                progressbar = wrapper.find('.progress').children(),
                filename =  wrapper.find('.image_filename'),
                filepath =  wrapper.find('.image_filepath'),
                tempname = wrapper.find('.image_tempname'),
                temppath = wrapper.find('.image_temppath'),
                preview = wrapper.find('.preview img'),
                cropButtons = wrapper.find('.crop_buttons'),
                additionalFields = wrapper.find('.additional_fields'),
                crop = $('#crop_image'),
                crop_preview = $('#crop_preview'),
                deleteButton = wrapper.find('.delete');

            deleteButton.on('click', function (event) {
                event.preventDefault();

                cropButtons.slideUp();
                additionalFields.slideUp();

                $(this).hide();

                additionalFields.find('input').each(function () {
                    $(this).val('');
                });

                preview.attr('src', '');
                filename.val('');
                tempname.val('');
                temppath.val('');
                filepath.val('');
                crop.attr('src', '');
                crop_preview.attr('src', '');

                if (preview.hasClass('placeholder')) {
                    preview.data('src', 'holder.js/100px200?auto=yes&font=\'Font Awesome 5 Free\'&text=&#xf1c5;');
                    Holder.setResizeUpdate(preview.get(0), false);
                }

                $input.closest('.form-group').find('.start-cropper').removeClass('btn-primary').addClass('btn-default');
                $input.closest('.form-group').find('.crop-data').val('');
            });

            let uploader = new plupload.Uploader({
                browse_button: this,
                url: url,
                filters: {
                    mime_types: "image/gif,image/jpeg,image/pjpeg,image/png"
                },
                chunk_size: '500kb',
                multi_selection: false,
                file_data_name: name,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                multipart_params: {
                    fieldName: name
                }
            });

            uploader.init();

            uploader.bind('FilesAdded', function (up, files) {
                wrapper.find('.ibox-content').toggleClass('sk-loading');
                progressbar.parent().slideDown();
                up.start();
            });

            uploader.bind('UploadProgress', function (up, file) {
                progressbar.width(file.percent + '%');
                progressbar.attr('aria-valuenow', file.percent);
            });

            uploader.bind('FileUploaded', function (up, file, response) {
                wrapper.find('.ibox-content').toggleClass('sk-loading');
                progressbar.parent().slideUp();
                progressbar.width('0%');
                progressbar.attr('aria-valuenow', 0);

                response = JSON.parse(response.response);

                preview.attr('src', response.result.tempPath);
                filename.val(file.name);
                tempname.val(response.result.tempName);
                temppath.val(response.result.tempPath);
                filepath.val(response.result.tempPath);
                crop.attr('src', response.result.tempPath);
                crop_preview.attr('src', response.result.tempPath);
                if (preview.hasClass('placeholder')) {
                    Holder.setResizeUpdate(preview.get(0), false);
                }

                additionalFields.find('input').each(function () {
                    $(this).val('');
                });

                $input.closest('.form-group').find('.start-cropper').removeClass('btn-primary').addClass('btn-default');
                $input.closest('.form-group').find('.crop-data').val('');
                if (file.type == 'image/gif') {
                    cropButtons.slideUp();
                } else {
                    cropButtons.slideDown();
                }

                deleteButton.show();

                additionalFields.slideDown();
            });
        });
    }

    if (container.find('.start-cropper').length > 0) {
        container.find('.start-cropper').each(function () {
            $(this).on('click', function (event) {
                event.preventDefault();

                let cropSettings = JSON.parse($(this).attr('data-crop-settings'));

                $('#crop_modal .modal-title').text($(this).closest('.form-group').children('label').text());
                $('#crop_modal .description').text(cropSettings.description);
                $('#crop_image').attr('data-ratio', $(this).attr('data-ratio'));

                $('#crop_modal .crop-size').attr('data-width', cropSettings.width);
                $('#crop_modal .crop-size').attr('data-height', cropSettings.height);
                $('#crop_modal .crop-size').attr('data-type', cropSettings.type);

                let uniqID = UUID.generate();

                $(this).attr('data-crop-button', uniqID);
                let $cropField = $(this).next().attr('data-crop', uniqID);
                $('#crop_modal .save').attr('data-target', uniqID);
                $('#crop_image').attr('data-values', $cropField.val());

                let imageSrc = $(this).closest('.form-group').find('img').attr('src');
                $('#crop_image').attr('src', imageSrc);
                $('#crop_preview').attr('src', imageSrc);

                $('#crop_modal').modal();
            });
        });

        $('#crop_modal').on('hidden.bs.modal', function () {
            let $image = $('#crop_image');

            $image.cropper('destroy');
            $('#crop_modal .modal-title').text('');
            $('#crop_modal .save').removeAttr('data-crop-field');
            $image.removeAttr('data-ratio');
            $image.removeAttr('data-values');
        });

        $('#crop_modal').on('shown.bs.modal', function () {
            let $image = $('#crop_image');

            let cropperOptions = {
                viewMode: 2,
                preview: "#crop_modal .img-preview",
                ready: function () {
                    //$('.img-preview').parent().show();
                }
            };

            if ($image.attr('data-ratio')) {
                let size = $image.attr('data-ratio').split('/');
                cropperOptions.aspectRatio = parseInt(size[0]) / parseInt(size[1]);
            } else {
                return;
            }

            if ($image.attr('data-values')) {
                cropperOptions.data = JSON.parse($image.attr('data-values'));

                if (typeof cropperOptions.data == 'string') {
                    cropperOptions.data = JSON.parse(cropperOptions.data);
                }
            }

            $image.on({
                crop: function (e) {
                    let infoContainer = $('#crop_modal .crop-size'),
                        requiredWidth = infoContainer.attr('data-width'),
                        requiredHeight = infoContainer.attr('data-height'),
                        requiredType = infoContainer.attr('data-type'),
                        width = Math.round(e.detail.width),
                        height = Math.round(e.detail.height);

                    infoContainer.removeClass('label-primary').removeClass('label-danger');

                    switch (requiredType) {
                        case 'min':
                            if (width < requiredWidth || height < requiredHeight) {
                                infoContainer.addClass('label-danger');
                            } else {
                                infoContainer.addClass('label-primary');
                            }
                            break;
                        case 'fixed':
                            if (width !== requiredWidth && height !== requiredHeight) {
                                infoContainer.addClass('label-danger');
                            } else {
                                infoContainer.addClass('label-primary');
                            }
                            break;
                    }

                    infoContainer.text(width + 'x' + height);
                }
            }).cropper(cropperOptions);
        });

        $('#crop_modal').on('click', '[data-method]', function () {
            let $this = $(this),
                $image = $('#crop_image'),
                data = $this.data(),
                $target,
                result;

            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                            console.log(e.message);
                        }
                    }
                }

                switch (data.method) {
                    case 'rotate':
                        $image.cropper('clear');
                        break;
                }

                result = $image.cropper(data.method, data.option, data.secondOption);

                switch (data.method) {
                    case 'rotate':
                        $image.cropper('crop');
                        break;

                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;
                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        console.log(e.message);
                    }
                }

            }
        });

        $('#crop_modal').on('click', '.save', function (event) {
            event.preventDefault();

            let $image = $('#crop_image'),
                cropData = JSON.stringify($image.cropper('getData')),
                fieldSelector = $(this).attr('data-target'),
                $field = $('[data-crop=' + fieldSelector + ']'),
                $link = $('[data-crop-button=' + fieldSelector + ']');

            $field.val(cropData);

            $link.removeClass('btn-default').addClass('btn-primary');

            $('#crop_modal').modal('hide');
        });
    }
};
