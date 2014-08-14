<?php namespace Oven;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Oven\Exception\InvalidRecipeException;
use Oven\Exception\RecipeNotFoundException;

class Reader {

    const RECIPEFILE = 'recipe.json';

    protected $file;

    protected $recipefile;

    protected $recipe;

    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    public function load($file)
    {
        $this->recipefile = is_null($file) ? getcwd().'/'.self::RECIPEFILE : $file;

        return $this->validateRecipe($this->recipefile);
    }

    public function getFilesystem()
    {
        return $this->file;
    }

    public function getRecipePath()
    {
        return str_replace(self::RECIPEFILE, '', $this->recipefile);
    }

    public function getRecipeName()
    {   
        return Arr::get($this->recipe, 'name', null);
    }

    public function getIngredient($key)
    {
        return Arr::get($this->recipe, "ingredients.{$key}", null);
    }

    public function getAllIngredients()
    {
        return Arr::get($this->recipe, "ingredients", null);
    }

    protected function validateRecipe($file)
    {
        if ( ! $this->file->exists($file)) {
            throw new RecipeNotFoundException("Unable to locate {$file}");
        }

        $recipe = json_decode($this->file->get($file), true);

        if ( ! is_array($recipe)) {
            throw new InvalidRecipeException("{$file} is not a valid json file");
        }

        if ( ! array_key_exists('ingredients', $recipe)) {
            throw new InvalidRecipeException("{$file} is missing the ingredients");
        }

        $this->recipe = $recipe;

        return true;
    }

}
