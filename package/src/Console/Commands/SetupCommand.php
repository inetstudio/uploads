<?php

namespace InetStudio\UploadsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:uploads-package:setup';

    protected $description = 'Setup Uploads package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Uploads setup',
                'command' => 'inetstudio:uploads-package:uploads:setup',
            ],
        ];
    }
}
