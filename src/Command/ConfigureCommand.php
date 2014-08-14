<?php namespace Oven\Command;

use Oven\Config;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends Command {

    use CommandTrait;

    protected function configure()
    {
        $this->setName('recipe:configure')
            ->setDescription('Set default recipe path')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to your recipe files');
    }

    protected function fire()
    {
        $configurator = new Config(
            $this,
            new Filesystem
        );

        $configurator->run();
    }

}
