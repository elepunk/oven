<?php namespace Oven\Command;

use Oven\Factory;
use Oven\Recipe\Configurator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends Command implements ConsoleInterface {

    use CommandTrait;

    protected function configure()
    {
        $this->setName('recipe:configure')
            ->setDescription('Set path to recipe files')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to the recipe files');
    }

    /**
     * {@inheritdoc}
     */
    public function fire()
    {
        $configurator = new Configurator(new Filesystem);
        $factory = new Factory($this, $configurator);

        $factory->run();
    }

}
