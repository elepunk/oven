<?php namespace Oven;

use Illuminate\Filesystem\Filesystem;
use Oven\Command\ConfigureCommand;
use Oven\Command\CommandInterface;

class Config implements CommandInterface {

    const CONFIGFILE = 'oven.json';

    protected $command;

    protected $filesystem;

    public function __construct(ConfigureCommand $command, Filesystem $filesystem)
    {
        $this->command = $command;
        $this->filesystem = $filesystem;
    }

    public function run()
    {
        if ( ! $this->filesystem->isDirectory(__DIR__.'/../.oven')) {
            $this->buildConfigFile();
        }

        $path = $this->command->argument('path');

        $this->setConfig($path);

        $this->command->say('info', "Your recipe path is now set to {$path}");
    }

    protected function buildConfigFile()
    {
        $directory = $this->getDirectory();

        $this->filesystem->makeDirectory($directory);
        $this->filesystem->put($directory.'/'.self::CONFIGFILE, '');
    }

    protected function setConfig($path)
    {
        $directory = $this->getDirectory();

        $configs = [
            'recipe_path' => $path 
        ];

        $this->filesystem->put($directory.'/'.self::CONFIGFILE, json_encode($configs, 448));
    }

    protected function getDirectory()
    {
        return __DIR__.'/../.oven';
    }

}
