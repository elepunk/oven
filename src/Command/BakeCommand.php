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

class BakeCommand extends Command {

    use CommandTrait;

    /**
     *
     */
    protected function configure()
    {

    }

    /**
     *
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