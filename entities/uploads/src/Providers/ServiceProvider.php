<?php

namespace InetStudio\UploadsPackage\Uploads\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerViewComponents();
    }

    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\UploadsPackage\Uploads\Console\Commands\SetupCommand',
                'InetStudio\UploadsPackage\Uploads\Console\Commands\CreateFoldersCommand',
            ]);
        }
    }

    protected function registerPublishes(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'inetstudio.uploads-package.uploads');
    }

    protected function registerViewComponents(): void
    {
        Blade::componentNamespace('InetStudio\UploadsPackage\Uploads\View\Components', 'inetstudio.uploads-package.uploads');
    }
}
