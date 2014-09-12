<?php namespace Oven;

use Oven\Recipe\Parser;
use Oven\Recipe\Reader;
use Illuminate\Support\Arr;
use Oven\Recipe\Ingredient;
use Oven\Exception\InvalidRecipeException;

class Worker {

    /**
     * @var \Oven\Recipe\Reader
     */
    protected $reader;

    /**
     * @var array
     */
    protected $generated = [];

    /**
     * @var string
     */
    protected $destination;

    /**
     * Create new Worker instance
     *
     * @param \Oven\Recipe\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Build the recipe
     *
     * @param string $recipe
     * @param string $output
     * @param string $destination
     * @param array $items
     * @param array $arguments
     * @throws \Oven\Exception\InvalidRecipeException
     * @return void
     */
    public function build($recipeFile, $output, $destination, array $items, array $arguments)
    {
        $this->destination = $this->setDestination($destination, $output, Arr::get($arguments, 'namespace', true));

        $recipe = $this->reader->read($recipeFile);

        if (empty($items)) {
            $items = $recipe->getItem('ingredients');

            if (is_null($items)) {
                throw new InvalidRecipeException("There are no ingredients found in the recipe");
            }
        }

        $source = $this->getRecipePath($recipeFile);

        foreach ($items as $item) {
            $this->generated[] = $this->copySource($item, $output, $source, Arr::get($arguments, 'force', false));
        }

        return $this->generated;
    }

    /**
     * Set the path for the newly created files
     *
     * @param string $path
     * @param string $output
     * @param boolean $namespace
     * @return string
     */
    protected function setDestination($path, $output, $namespace = true)
    {
        $filesystem = $this->reader->filesystem();

        if ($namespace) {
            $directory = Parser::path($output);
            $path = $path.'/'.$directory;
        }

        if ( ! $filesystem->isDirectory($path)) {
            $filesystem->makeDirectory($path, 0755, true);
        }

        return $path;
    }

    /**
     * Copy and create new file
     *
     * @param string $item
     * @param string $output
     * @param bool $force
     * @throws \Oven\Exception\InvalidRecipeException
     * @return string
     */
    protected function copySource($item, $output, $source, $force = false)
    {
        $ingredient = $this->reader->getItem($item);

        if (is_null($ingredient)) {
            throw new InvalidRecipeException("Missing {$item} ingredient from the recipe");
        }

        $ingredient = new Ingredient($ingredient);

        if ( ! $ingredient->getIngredient()->offsetExists('source')) {
            throw new InvalidRecipeException("Unable  to locate {$item} ingredient source");
        }

        $filesystem = $this->reader->filesystem();
        $target = $this->destination.'/'.$ingredient->getSourceDir();

        if ( ! $filesystem->isDirectory($target)) {
            $filesystem->makeDirectory($target, 0755, true);
        }

        if ($ingredient->isEmptyDir()) {
            return $target;
        }

        $filename = $ingredient->getFilename($output);
        if ($filesystem->exists($target.'/'.$filename) and $force == false) {
            return $target.'/'.$filename;
        }

        $sourceFile = $source.'/'.$ingredient->getSourceFile();
        $content = Parser::buildSource($filesystem->get($sourceFile), $output);

        $filesystem->put($target.'/'.$filename, $content);

        return $target.'/'.$filename;
    }

    /**
     * Get the path of the recipe file
     *
     * @param string $recipeFile
     * @return string
     */
    protected function getRecipePath($recipeFile)
    {
        $path = str_replace('recipe.json', '', $recipeFile);

        return trim($path, '/');
    }

}
