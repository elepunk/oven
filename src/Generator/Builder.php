<?php namespace Oven\Generator;

use Oven\Command\BakeCommand;
use Illuminate\Filesystem\Filesystem;

class Builder {

    const RECIPEFILE = 'recipe.json';

    protected $command;

    protected $file;

    public function __construct(BakeCommand $command, Filesystem $file)
    {
        $this->command = $command;
        $this->file = $file;
    }

    public function build()
    {
        list($recipe, $ingredients) = $this->validateRecipe($this->command->option('r'));

        $this->command->say('info', 'Success! Your recipe is baked to perfection');
    }

    protected function validateRecipe($recipe)
    {
        if (is_null($recipe)) {
            $recipe = __DIR__.'/'.self::RECIPEFILE;
        }

        if ( ! $this->file->exists($recipe)) {
            $this->command->say('error', 'Error! Cannot find any recipe file to work with.');
            $this->command->say('comment', 'Use -r to specify a recipe file or create a new recipe.json');
        }

        $recipe = $this->file->get($recipe);
        $ingredients = json_decode($recipe, true);

        if ( ! is_array($ingredients)) {
            $this->command->say('error', 'Error! Unable to parse recipe file');
        }

        return [$recipe, $ingredients];
    }

}
