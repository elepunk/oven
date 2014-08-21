<?php namespace Oven;

use Illuminate\Filesystem\Filesystem;
use Oven\Command\ConfigureCommand;
use Oven\Command\CommandInterface;

class Config implements CommandInterface {

    /**
     * Oven config file
     */
    const CONFIGFILE = 'oven.json';

    /**
     * Oven config configuration command instance
     * 
     * @var Oven\Comman\ConfigureCommand
     */
    protected $command;

    /**
     * Illuminate filesystem instance
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create new Config instance
     * 
     * @param Oven\Command\ConfigureCommand $command
     * @param Illuminate\Filesystem\Filesystem       $filesystem
     */
    public function __construct(ConfigureCommand $command, Filesystem $filesystem)
    {
        $this->command = $command;
        $this->filesystem = $filesystem;
    }

    /**
     * Run configuration command
     * 
     * @return void
     */
    public function run()
    {
        if ( ! $this->filesystem->isDirectory(__DIR__.'/../.oven')) {
            $this->buildConfigFile();
        }

        $path = $this->command->argument('path');

        $this->setConfig($path);

        $this->command->say('info', "Your recipe path is now set to {$path}");
    }

    /**
     * Create oven config directory and file
     * 
     * @return void
     */
    protected function buildConfigFile()
    {
        $directory = $this->getDirectory();

        $this->filesystem->makeDirectory($directory);
        $this->filesystem->put($directory.'/'.self::CONFIGFILE, '');
    }

    /**
     * Add configuration into config file
     * 
     * @param string $path
     * @return  void
     */
    protected function setConfig($path)
    {
        $directory = $this->getDirectory();

        $configs = [
            'recipe_path' => $path 
        ];

        $this->filesystem->put($directory.'/'.self::CONFIGFILE, json_encode($configs, 448));
    }

    /**
     * Get oven config directory
     * 
     * @return string
     */
    protected function getDirectory()
    {
        return __DIR__.'/../.oven';
    }

}
