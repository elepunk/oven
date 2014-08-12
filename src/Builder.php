<?php namespace Oven;

use Oven\Command\BakeCommand;
use Illuminate\Filesystem\Filesystem;

class Builder {

    protected $command;

    protected $recipe;

    public function __construct(BakeCommand $command, Reader $recipe)
    {
        $this->command = $command;
        $this->recipe = $recipe;
    }

    public function build()
    {
        $recipe = $this->recipe->load($this->command->option('r'));

        if ( ! is_array($recipe)) {
            $this->command->say('error', $recipe);
        }
    }

}
