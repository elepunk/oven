<?php namespace Oven\Command;

use Oven\Generator\Builder;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BakeCommand extends Command {

    use CommandTrait;

    protected function configure()
    {
        $this->setName('bake')
            ->setDescription('Start baking some codes')
            ->addOption('r', null, InputOption::VALUE_OPTIONAL, 'Path to the recipe file')
            ->addOption('d', null, InputOption::VALUE_OPTIONAL, 'Destination where do you want the files to be generated')
            ->addOption('i', null, InputOption::VALUE_OPTIONAL, 'Item that you want to generate')
            ->addArgument('item', InputArgument::REQUIRED, 'Name of the item that you are generating');

    }

    protected function fire()
    {
        $builder = new Builder($this, new Filesystem);
        $builder->build();
    }

}
