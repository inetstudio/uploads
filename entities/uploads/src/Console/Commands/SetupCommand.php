<?php

namespace InetStudio\UploadsPackage\Uploads\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:uploads-package:uploads:setup';

    protected $description = 'Setup uploads package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Publish spatie media-library migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Migration',
                'command' => 'migrate',
            ],
            [
                'type' => 'artisan',
                'description' => 'Create folders',
                'command' => 'inetstudio:uploads-package:uploads:folders',
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish spatie media-library config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'config',
                ],
            ],
        ];
    }
}
