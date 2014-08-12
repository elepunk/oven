<?php namespace Oven;

use Illuminate\Filesystem\Filesystem;

class Reader {

    const RECIPEFILE = 'recipe.json';

    protected $file;

    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    public function load($file)
    {
        $file = is_null($file) ? __DIR__.'/'.self::RECIPEFILE : $file;

        if ( ! $this->file->exists($file)) {
            return 'Error! Cannot load recipe file. Make sure the path is correct';
        }
    }

    protected function validateRecipe()
    {

    }

}
