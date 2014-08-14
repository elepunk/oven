<?php namespace Oven;

use Oven\Reader;
use Illuminate\Support\Arr;
use Oven\Exception\BuildProcessException;
use Oven\Exception\InvalidRecipeException;
use Oven\Exception\RecipeNotFoundException;
use Illuminate\Filesystem\FileNotFoundException;

class Generator {

    const CONFIGFILE = 'oven.json';

    const RECIPEFILE = 'recipe.json';

    protected $recipe;

    public function __construct(Reader $recipe)
    {
        $this->recipe = $recipe;
    }

    public function start($recipeFile, $destination, $output, $items, $template = false, $force = false)
    {
        if ($template) {
            $recipeFile = $this->recipeLocator($recipeFile);
        }

        $this->recipe->load($recipeFile);

        if (is_null($items)) {
            $items = array_keys($this->recipe->getAllIngredients());
        }

        $generated = [];

        foreach ($items as $item) {
            $file = $this->generate($destination, $item, $output, $force);

            $generated[] = $file;
        }

        return $generated;
    }

    protected function recipeLocator($recipe)
    {
        $filesystem = $this->recipe->getFilesystem();
        $basePath = __DIR__.'/../.oven/'.self::CONFIGFILE;

        try {
            $content = json_decode($filesystem->get($basePath), true);
        } catch (FileNotFoundException $e) {
            throw new RecipeNotFoundException('Oven cannot locate your default resipe source. Run oven recipe:configure your-recipe-path first');
        }
        
        $recipePath = Arr::get($content, 'recipe_path', null);

        if (is_null($recipePath)) {
            throw new RecipeNotFoundException('Oven cannot locate your default resipe source. Run oven recipe:configure your-recipe-path first');
        }

        return $recipePath.'/'.$recipe.'/'.self::RECIPEFILE;
    }

    protected function generate($destination, $item, $output, $force)
    {
        $destination = $destination.'/'.Parser::path($output);
        $filesystem = $this->recipe->getFilesystem();
        
        if ( ! $filesystem->isDirectory($destination)) {
            $filesystem->makeDirectory($destination, 0755, true);
        }

        $ingredients = $this->recipe->getIngredient($item);

        if (is_null($ingredients)) {
            throw new InvalidRecipeException("Error! Missing {$item} ingredient from the recipe");
        }

        if (is_null(Arr::get($ingredients, 'source', null))) {
            throw new InvalidRecipeException("Error! Cannot locate {$item} ingredient source");
        }

        $process = $this->copySource(Arr::get($ingredients, 'source'), Arr::get($ingredients, 'name'), $output, $destination, $force);

        if (! $process) {
            throw new BuildProcessException("Error! Item already exists. Use -f to overwrite");
        }

        return $process;
    }

    protected function copySource($source, $output, $argument, $destination, $force)
    {
        $filesystem = $this->recipe->getFilesystem();

        $filename = basename($source);
        $sourceDir = str_replace($filename, '', $source);
        $target = Parser::extract($output, $argument);

        if ( ! $filesystem->isDirectory($destination.'/'.$sourceDir)) {
            $filesystem->makeDirectory($destination.'/'.$sourceDir, 0755, true);
        }

        if ($filesystem->exists($destination.'/'.$sourceDir.$target) and ! $force) {
            return false;
        } 

        $content = $this->readSource($this->recipe->getRecipePath().'/'.$source, $argument);
        $filesystem->put($destination.'/'.$sourceDir.$target, $content);
        
        return $destination.'/'.$sourceDir.$target;
    }

    protected function readSource($source, $argument)
    {
        $filesystem = $this->recipe->getFilesystem();
        $raw = $filesystem->get($source);

        $clean = Parser::buildSource($raw, $argument);

        return $clean;
    }

}
