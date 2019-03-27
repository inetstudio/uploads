@php
    $name =  isset($attributes['field']['name']) ? $attributes['field']['name'] : $name;
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group row @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']) && $attributes['label']['title'] == '')
        <label for="{{ $name }}" class="col-sm-2 col-form-label font-bold"></label>
    @elseif (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 col-form-label font-bold']) !!}
    @endif

    <div class="col-sm-10">

        @if ($value)
            <ul class="list-unstyled file-list">
                <li><a href="{{ $value->getFullUrl() }}" target="_blank"><i class="fa fa-file"></i> {{ $value->name }}</a></li>
            </ul>
        @endif

        <div class="input-group fileinput-new" data-provides="fileinput">
            <div class="form-control" data-trigger="fileinput">
                <i class="fa fa-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
            </div>

            <div class="input-group-append">
                <span class="input-group-addon btn-file btn-default">
                    <span class="fileinput-new">Выберите файл</span>
                    <span class="fileinput-exists">Изменить</span>
                    <input type="file" name="{{ $name }}">
                </span>
                <span class="input-group-addon fileinput-remove fileinput-exists btn-default" data-dismiss="fileinput">
                  Удалить
                </span>
            </div>
        </div>

    </div>
</div>

@if (!(isset($attributes['hr']) && $attributes['hr']['show'] == false))
    <div class="hr-line-dashed"></div>
@endif
