<?php namespace Oven\Recipe;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Oven\Exception\InvalidRecipeException;
use Oven\Exception\RecipeNotFoundException;

class Reader {

    /**
     * @var array
     */
    protected $recipe = [];

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create new Reader instance
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Load oven recipe file
     *
     * @param string $recipeFile
     * @throws \Oven\Exception\RecipeNotFoundException
     * @return \Oven\Recipe\Reader
     */
    public function read($recipeFile)
    {
        if ( ! $this->filesystem->exists($recipeFile)) {
            throw new RecipeNotFoundException("Unable to load {$recipeFile}");
        }

        $this->recipe = $this->parseRecipe($recipeFile);

        return $this;
    }

    /**
     * Get recipe contents
     *
     * @return array
     */
    public function getContent()
    {
        return $this->recipe;
    }

    /**
     * Get recipe item
     *
     * @param string $key
     * @return mixed
     */
    public function getItem($key)
    {
        return Arr::get($this->recipe, $key, null);
    }

    /**
     * Parse recipe file contents
     *
     * @param string $recipeFile
     * @throws \Oven\Exception\InvalidRecipeException
     * @return array
     */
    protected function parseRecipe($recipeFile)
    {
        $content = $this->filesystem->get($recipeFile);
        $contentArr = json_decode($content, true);

        if ( ! is_array($contentArr)) {
            throw new InvalidRecipeException("Unable to parse {$recipeFile} content");
        }

        return $contentArr;
    }

}
