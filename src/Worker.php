<?php namespace Oven;

use Oven\Recipe\Reader;
use Oven\Recipe\Parser;
use Illuminate\Support\Arr;
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

        foreach ($items as $item) {
            $this->generated[] = $this->copySource($item, $output, Arr::get($arguments, 'force', false));
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
            $directory = Parser::getPath($output);
            $destination = $path.'/'.$directory;
        }

        if ( ! $filesystem->isDirectory($destination)) {
            $filesystem->makeDirectory($destination, 0755, true);
        }

        return $destination;
    }

    /**
     *
     */
    protected function copySource($item, $output, $force = false)
    {
        $ingredient = $this->reader->getItem($item);
        if (is_null($ingredient)) {
            throw new InvalidRecipeException("Missing {$item} ingredient from the recipe");
        }

        if (is_null(Arr::get($ingredient, 'source', null))) {
            throw new InvalidRecipeException("Unable  to locate {$item} ingredient source");
        }
    }

}
