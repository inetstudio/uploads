<?php

namespace InetStudio\Uploads\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerFormComponents();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\Uploads\Console\Commands\SetupCommand',
                'InetStudio\Uploads\Console\Commands\CreateFoldersCommand',
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );
    }

    /**
     * Регистрация путей.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.uploads');
    }

    /**
     * Регистрация компонентов форм.
     */
    protected function registerFormComponents()
    {
        FormBuilder::component('crop', 'admin.module.uploads::back.forms.fields.crop', ['name', 'value', 'attributes']);
        FormBuilder::component('files', 'admin.module.uploads::back.forms.fields.files', ['name', 'value', 'attributes']);
        FormBuilder::component('imagesStack', 'admin.module.uploads::back.forms.stacks.images', ['name', 'value', 'attributes']);
    }
}
