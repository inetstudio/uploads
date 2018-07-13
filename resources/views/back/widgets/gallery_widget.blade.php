@php
    $images = [
        'media' => [],
        'fields' => [
            [
                'title' => 'Описание',
                'name' => 'description',
            ],
            [
                'title' => 'Copyright',
                'name' => 'copyright',
            ],
            [
                'title' => 'Alt',
                'name' => 'alt',
            ],
        ],
    ];
@endphp
@pushonce('modals:gallery')
    <div id="gallery_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title">Галерея</h1>
                </div>
                <div class="modal-body">
                    <div class="ibox-title">
                        <a href="#" class="btn btn-sm btn-primary btn-lg add-images">Добавить</a>
                    </div>
                    <div class="ibox-content form-horizontal">
                        <div class="sk-spinner sk-spinner-double-bounce">
                            <div class="sk-double-bounce1"></div>
                            <div class="sk-double-bounce2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <form action="{{ route('back.widgets.gallery.save') }}">

                                            {!! Form::imagesStack('gallery', 'gallery',
                                                array_merge(
                                                    $images,
                                                    [
                                                        'controls' => ['edit', 'remove']
                                                    ]
                                                )
                                            ) !!}

                                        </form>
                                    </div>
                                </div>
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
@endpushonce
