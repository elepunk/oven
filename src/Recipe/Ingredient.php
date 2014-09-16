<?php namespace Oven\Recipe;

use Illuminate\Support\Fluent;

class Ingredient {

    /**
     * Ingredient items
     *
     * @var \Illuminate\Support\Fluent
     */
    protected $ingredient;

    /**
     * Create new Ingredient class
     *
     * @param array $ingredient
     */
    public function __construct($ingredient = array())
    {
        $this->ingredient = new Fluent($ingredient);
    }

    /**
     * Get the ingredient items
     *
     * @return \Illuminate\Support\Fluent
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Extract source file directory
     *
     * @return string
     */
    public function getSourceDir()
    {
        $source = $this->ingredient->get('source');

        if ( ! $this->isEmptyDir()) {
            $filename = basename($source);
            return trim(str_replace($filename, '', $source), '/');
        }

        return $source;
    }

    /**
     * @return mixed
     */
    public function getSourceFile()
    {
        return $this->ingredient->get('source');
    }

    /**
     * @param $output
     */
    public function getFilename($output)
    {
        $name = $this->ingredient->get('name');

        return Parser::extract($name, $output);
    }

    /**
     * Determing if it is just an empty directory
     *
     * @return bool
     */
    public function isEmptyDir()
    {
        $emptyDir = $this->ingredient->get('empty-dir');

        if (is_null($emptyDir)) {
            return false;
        }

        return $emptyDir;
    }

}