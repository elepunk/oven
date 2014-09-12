<?php namespace Oven\Command;

use Oven\Builder;
use Oven\Worker;
use Oven\Recipe\Reader;
use Oven\Recipe\Configurator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BakeCommand extends Command implements ConsoleInterface {

    use CommandTrait;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('recipe:bake')
            ->setDescription('Start baking some codes')
            ->addOption('recipe', null, InputOption::VALUE_OPTIONAL, 'Path to the recipe file')
            ->addOption('destination', null, InputOption::VALUE_OPTIONAL, 'Destination where do you want the files to be generated')
            ->addOption('f', null, InputOption::VALUE_NONE, 'Force overwrite existing items')
            ->addOption('ignore-namespace', null, InputOption::VALUE_NONE, 'Ignore namespace and create in the same directory')
            ->addArgument('output', InputArgument::REQUIRED, 'Name of the item that you are generating')
            ->addArgument('recipe-name', InputArgument::OPTIONAL, 'Recipe from your default location')
            ->addArgument('items', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Items that you want to generate (separate items with spaces)');
    }

    /**
     * {@inheritdoc}
     */
    public function fire()
    {
        $filesystem = new Filesystem;
        $reader = new Reader($filesystem);

        $builder = new Builder(
            $this,
            new Configurator($filesystem),
            new Worker($reader)
        );

        return $builder->run();
    }

}