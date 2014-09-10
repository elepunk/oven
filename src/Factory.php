<?php namespace Oven;

use Oven\Recipe\Configurator;
use Oven\Command\ConfigureCommand;
use Oven\Command\CommandInterface;

class Factory implements CommandInterface {

    /**
     * @var \Oven\Command\ConfigureCommand
     */
    protected $command;

    /**
     * @var \Oven\Recipe\Configurator
     */
    protected $configurator;

    /**
     * Create new Factory instance
     *
     * @param \Oven\Command\ConfigureCommand
     * @param \Oven\Recipe\Configurator $configurator
     */
    public function __construct(ConfigureCommand $command, Configurator $configurator)
    {
        $this->command = $command;
        $this->configurator = $configurator;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $recipePath = $this->command->argument('path');

        $this->configurator->setup($recipePath);

        $this->command->say('info', "Your recipe path is now set to {$recipePath}");
    }

}


