<?php namespace Oven;

use Oven\Recipe\Configurator;
use Oven\Command\BakeCommand;
use Oven\Command\CommandInterface;

class Builder implements CommandInterface {

    /**
     *
     */
    const RECIPEFILE = 'recipe.json';

    /**
     * @var \Oven\Command\BakeCommand
     */
    protected $command;

    /**
     * @var \Oven\Recipe\Configurator
     */
    protected $configurator;

    /**
     * @var
     */
    protected $worker;

    /**
     * Create new Builder instance
     *
     * @param BakeCommand $command
     * @param Configurator $configurator
     */
    public function __construct(BakeCommand $command, Configurator $configurator, Worker $worker)
    {
        $this->command = $command;
        $this->configurator = $configurator;
        $this->worker = $worker;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $arguments = [
            'force' => $this->isForce(),
            'namespace' => $this->ignoreNamespace()
        ];

        $files = $this->worker->build(
            $this->setRecipe(),
            $this->setOutput(),
            $this->setDestination(),
            $this->setItems(),
            $arguments
        );

        $total = count($files);
        if ($total > 1) {
            $message = "Successfully generated {$total} files";
        } else {
            $message = "Successfully generated {$total} file";
        }

        $this->command->say('info', $message);

        foreach ($files as $file) {
            $this->command->say('comment', $file);
        }
    }

    /**
     * @return string
     */
    protected function setRecipe()
    {
        if ( ! is_null($this->command->option('recipe'))) {
            return $this->command->option('recipe');
        }

        $recipe = $this->command->argument('recipe-name');
        $recipePath = $this->configurator->getRecipePath();

        return $recipePath.'/'.$recipe.'/'.self::RECIPEFILE;
    }

    /**
     * @return mixed
     */
    protected function setOutput()
    {
        return $this->command->argument('output');
    }

    /**
     * @return string
     */
    protected function setDestination()
    {
        if (is_null($this->command->option('destination'))) {
            return getcwd();
        }

        return $this->command->option('destination');
    }

    /**
     *
     */
    protected function setItems()
    {
        if ( ! empty($this->command->argument('items'))) {
            return $this->command->argument('items');
        }

        return [];
    }

    /**
     * @return mixed
     */
    protected function isForce()
    {
        return $this->command->option('f');
    }

    /**
     * @return mixed
     */
    protected function ignoreNamespace()
    {
        return $this->command->option('ignore-namespace');
    }

}