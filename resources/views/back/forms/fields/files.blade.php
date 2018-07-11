@php
    $name =  isset($attributes['field']['name']) ? $attributes['field']['name'] : $name;
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
@endphp

<div class="form-group @if ($errors->has($transformName)){!! "has-error" !!}@endif">

    @if (isset($attributes['label']['title']) && $attributes['label']['title'] == '')
        <label for="{{ $name }}" class="col-sm-2 control-label"></label>
    @elseif (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">

        @if ($value)
            <ul class="list-unstyled file-list">
                <li><a href="{{ $value->getFullUrl() }}" target="_blank"><i class="fa fa-file"></i> {{ $value->name }}</a></li>
            </ul>
        @endif

        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
            <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
            </div>
            <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">Выберите файл</span>
                <span class="fileinput-exists">Изменить</span>
                <input type="file" name="{{ $name }}"/>
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Удалить</a>
        </div>

    </div>
</div>

@if (!(isset($attributes['hr']) && $attributes['hr']['show'] == false))
    <div class="hr-line-dashed"></div>
@endif
