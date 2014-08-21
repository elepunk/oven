<?php namespace Oven;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Oven\Exception\InvalidRecipeException;
use Oven\Exception\RecipeNotFoundException;

class Reader {

    /**
     * Oven recipe file name
     */
    const RECIPEFILE = 'recipe.json';

    /**
     * Illuminate filesystem instance
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * Recipe file location
     * 
     * @var string
     */
    protected $recipefile;

    /**
     * Recipe contents
     * 
     * @var array
     */
    protected $recipe;

    /**
     * Create new Reader instance
     * 
     * @param Illuminate\Filesystem\Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    /**
     * Load recipe file
     * 
     * @param  string $file
     * @return boolean
     */
    public function load($file)
    {
        $this->recipefile = is_null($file) ? getcwd().'/'.self::RECIPEFILE : $file;

        return $this->validateRecipe($this->recipefile);
    }

    /**
     * Get Illuminate\Filesystem\Filesystem instance
     * 
     * @return Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->file;
    }

    /**
     * Get recipe file path
     * 
     * @return string
     */
    public function getRecipePath()
    {
        return str_replace(self::RECIPEFILE, '', $this->recipefile);
    }

    /**
     * Get current recipe name
     * 
     * @return string|null
     */
    public function getRecipeName()
    {   
        return Arr::get($this->recipe, 'name', null);
    }

    /**
     * Get an ingredient value
     * 
     * @param  string $key
     * @return mixed
     */
    public function getIngredient($key)
    {
        return Arr::get($this->recipe, "ingredients.{$key}", null);
    }

    /**
     * Get the ingredients from recipe file
     * 
     * @return array|null
     */
    public function getAllIngredients()
    {
        return Arr::get($this->recipe, "ingredients", null);
    }

    /**
     * Validate recipe file content
     * 
     * @param  string $file
     * @return boolean
     */
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
