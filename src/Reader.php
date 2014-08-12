<?php namespace Oven;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;

class Reader {

    const RECIPEFILE = 'recipe.json';

    protected $file;

    protected $recipe;

    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    public function load($file)
    {
        $file = is_null($file) ? __DIR__.'/'.self::RECIPEFILE : $file;

        return $this->validateRecipe($file);
    }

    public function getFilesystem()
    {
        return $this->file;
    }

    public function getRecipeName()
    {   
        return Arr::get($this->recipe, 'name', null);
    }

    public function getIngredient($key)
    {
        return Arr::get($this->recipe, "ingredients.{$key}", null);
    }

    protected function validateRecipe($file)
    {
        if ( ! $this->file->exists($file)) {
            return 'Error! Cannot load recipe file. Make sure the path is correct';
        }

        $recipe = json_decode($this->file->get($file), true);

        if ( ! is_array($recipe)) {
            return 'Error! Invalid recipe file';
        }

        if ( ! array_key_exists('ingredients', $recipe)) {
            return 'Error! Your recipe file is missing the ingredients';
        }

        $this->recipe = $recipe;

        return true;
    }

}
