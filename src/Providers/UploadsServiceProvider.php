<?php

namespace InetStudio\Uploads\Providers;

use Illuminate\Support\ServiceProvider;

class UploadsServiceProvider extends ServiceProvider
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
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
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
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.uploads');
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        // Controllers
        $this->app->bind('InetStudio\Uploads\Contracts\Http\Controllers\Back\UploadsControllerContract', 'InetStudio\Uploads\Http\Controllers\Back\UploadsController');

        // Events
        $this->app->bind('InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract', 'InetStudio\Uploads\Events\Back\UpdateUploadEvent');

        // Exceptions
        $this->app->bind('InetStudio\Uploads\Contracts\Exceptions\UploadExceptionContract', 'InetStudio\Uploads\Exceptions\UploadException');

        // Services
        $this->app->bind('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract', 'InetStudio\Uploads\Services\Back\ImagesService');
        $this->app->bind('InetStudio\Uploads\Contracts\Services\Back\FilesServiceContract', 'InetStudio\Uploads\Services\Back\FilesService');
        $this->app->bind('InetStudio\Uploads\Contracts\Services\UploaderServiceContract', 'InetStudio\Uploads\Services\UploaderService');
    }
}
