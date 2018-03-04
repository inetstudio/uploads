<?php

namespace InetStudio\Uploads\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        FormBuilder::component('crop', 'admin.module.uploads::back.forms.fields.crop', ['name', 'value', 'attributes']);
        FormBuilder::component('imagesStack', 'admin.module.uploads::back.forms.stacks.images', ['name', 'value', 'attributes']);
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
