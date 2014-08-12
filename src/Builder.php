<?php namespace Oven;

use Illuminate\Support\Arr;
use Oven\Command\BakeCommand;

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
        if ( ! $this->recipe->load($this->command->option('r'))) {
            $this->command->say('error', $recipe);
        }

        $destination = is_null($this->command->option('d')) ? __DIR__ : $this->command->option('d');
        $items = empty($this->command->argument('items')) ?
            array_keys($this->recipe->getAllIngredients()) : $this->command->argument('items');

        $process = $this->generate($destination, $this->command->argument('name'), $items);

        if ($process) {
            $this->command->say('info', 'Success! Your recipe is cooked to prefection');
        }
    }

    protected function generate($destination, $name, $items)
    {
        $destination = $destination.'/'.Parser::path($name);
        $filesystem = $this->recipe->getFilesystem();
        
        if ( ! $filesystem->isDirectory($destination)) {
            $filesystem->makeDirectory($destination, 0755, true);
        }

        foreach ($items as $item) {
            $ingredients = $this->recipe->getIngredient($item);

            if (is_null($ingredients)) {
                $this->command->say('error', "Error! Missing {$item} ingredient from the recipe");
                return false;
                break;
            }

            if (is_null(Arr::get($ingredients, 'source', null))) {
                $this->command->say('error', "Error! Cannot locate {$item} ingredient source");
                return false;
                break;
            }

            $this->copySource(Arr::get($ingredients, 'source'), Arr::get($ingredients, 'name'), $name, $destination);
        }

        return true;
    }

    protected function copySource($source, $name, $argument, $destination)
    {
        $filesystem = $this->recipe->getFilesystem();

        $filename = basename($source);
        $sourceDir = str_replace($filename, '', $source);
        $target = Parser::extract($name, $argument);

        if ( ! $filesystem->isDirectory($destination.'/'.$sourceDir)) {
            $filesystem->makeDirectory($destination.'/'.$sourceDir, 0755, true);
        }

        $content = $this->readSource($this->recipe->getRecipePath().'/'.$source, $argument);
        $filesystem->put($destination.'/'.$sourceDir.$target, $content);
    }

    protected function readSource($source, $argument)
    {
        $filesystem = $this->recipe->getFilesystem();
        $raw = $filesystem->get($source);

        $clean = Parser::buildSource($raw, $argument);

        return $clean;
    }

}
