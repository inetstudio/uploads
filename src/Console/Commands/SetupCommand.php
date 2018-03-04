<?php

namespace InetStudio\Uploads\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class SetupCommand.
 */
class SetupCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:uploads:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup uploads package';

    /**
     * Список дополнительных команд.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->initCommands();

        foreach ($this->calls as $info) {
            if (! isset($info['command'])) {
                continue;
            }

            $params = (isset($info['params'])) ? $info['params'] : [];

            $this->line(PHP_EOL.$info['description']);

            switch ($info['type']) {
                case 'artisan':
                    $this->call($info['command'], $params);
                    break;
                case 'cli':
                    $process = new Process($info['command']);
                    $process->run();
                    break;
            }
        }
    }

    /**
     * Инициализация команд.
     *
     * @return void
     */
    private function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Medialibrary setup',
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
                'command' => 'inetstudio:uploads:folders',
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish public',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Uploads\Providers\UploadsServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Publish medialibrary config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
                    '--tag' => 'config',
                ],
            ],
            [
                'type' => 'cli',
                'description' => 'Composer dump',
                'command' => 'composer dump-autoload',
            ],
        ];
    }
}
