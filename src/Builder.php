<?php namespace Oven;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Oven\Command\CommandInterface;
use Oven\Exception\BuildProcessException;
use Symfony\Component\Console\Command\Command;

class Builder implements CommandInterface {

    /**
     * Symfony command instance
     * 
     * @var Symfony\Component\Console\Command\Command
     */
    protected $command;

    /**
     * Oven generator instance
     * 
     * @var Oven\Generator
     */
    protected $generator;

    /**
     * Create new Builder instance
     * 
     * @param Symfony\Component\Console\Command\Command   $command
     * @param Oven\Generator $generator
     */
    public function __construct(Command $command, Generator $generator)
    {
        $this->command = $command;
        $this->generator = $generator;
    }

    /**
     * Run builder command
     * 
     * @return void
     */
    public function run()
    {
        $command = $this->command->getName();

        $destination = is_null($this->command->option('d')) ? getcwd() : $this->command->option('d');
        $items =  ! empty($this->command->argument('items')) ? $this->command->argument('items') : null;
        $output = $this->command->argument('name');

        $arguments = [
            'force' => $this->command->option('f');
            'namspace' => $this->command->option('ignore-namespace');
        ];

        if (Str::startsWith($command, 'recipe')) {
            $recipe = $this->command->argument('recipe');

            $files = $this->generator->start($recipe, $destination, $output, $items, true, $arguments);
        } else {
            $recipe = $this->command->option('r');

            $files = $this->generator->start($recipe, $destination, $output, $items, false, $arguments);
        }

        $items = count($files) > 1 ? Str::plural('item') : Str::singular('item');

        $this->command->say('info', 'Your recipe is cooked to prefection! Oven generated '.count($files).' '.$items.' for you');
        foreach ($files as $file) {
            $this->command->say('comment', $file);
        }
    }

}
