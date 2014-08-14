<?php namespace Oven\Command;

use Oven\Builder;
use Oven\Reader;
use Oven\Generator;
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
            ->setDescription('Bake some codes for me')
            ->addOption('r', null, InputOption::VALUE_OPTIONAL, 'Path to the recipe file')
            ->addOption('d', null, InputOption::VALUE_OPTIONAL, 'Destination where do you want the files to be generated')
            ->addOption('f', null, InputOption::VALUE_NONE, 'Force overwrite existing items')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the item that you are generating')
            ->addArgument('items', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Items that you want to generate (separate items with spaces)');
    }

    protected function fire()
    {
        $reader = new Reader(new Filesystem);

        $builder = new Builder(
            $this,
            new Generator($reader)
        );
        
        $builder->run();
    }

}
