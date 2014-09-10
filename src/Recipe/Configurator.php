<?php namespace Oven\Recipe;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;

class Configurator {

    /**
     * Oven configuration file
     */
    const CONFIGFILE = 'oven.json';

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create new Configurator instance
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Setup oven configurator
     *
     * @param string $recipePath
     * @return void
     */
    public function setup($recipePath)
    {
        $directory = $this->getDirectoryPath();

        if ( ! $this->filesystem->isDirectory($directory)) {
            $this->buildConfig();
        }

        $this->setConfigPath($recipePath);
    }

    /**
     * Get recipe files location
     *
     * @return string
     */
    public function getRecipePath()
    {
        $directory = $this->getDirectoryPath();

        $content = $this->filesystem->get($directory.'/'.self::CONFIGFILE);
        $contentArr = json_decode($content, true);

        return Arr::get($contentArr, 'recipe_path', null);
    }

    /**
     * Create oven config directory
     *
     * @return void
     */
    protected function buildConfig()
    {
        $directory = $this->getDirectoryPath();

        $this->filesystem->makeDirectory($directory);
        $this->filesystem->put($directory.'/'.self::CONFIGFILE, '');
    }

    /**
     * Set path to recipe files
     *
     * @param string $path
     * @return void
     */
    protected function setConfigPath($path)
    {
        $directory = $this->getDirectoryPath();

        $configs = [
            'recipe_path' => $path
        ];

        $this->filesystem->put($directory.'/'.self::CONFIGFILE, json_encode($configs, 448));
    }

    /**
     * Get oven config directory path
     *
     * @return string
     */
    protected function getDirectoryPath()
    {
        return __DIR__.'/../../.oven';
    }

}
