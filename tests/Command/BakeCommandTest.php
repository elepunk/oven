<?php namespace Oven\TestCase\Command;

use Mockery as m;
use Oven\Command\BakeCommand;

class BakeCommandTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testFireMethod()
    {
        $command = new BakeCommand;
        $mockInput = m::mock('Symfony\Component\Console\Input\InputInterface');
        $mockOutput = m::mock('Symfony\Component\Console\Ouput\OutputInterface');
        $command->input = $mockInput;
        $command->output = $mockOutput;

        $mockInput->shouldReceive('getOption')
            ->once();

        $mockInput->shouldReceive('getArgument')
            ->once();

        $command->fire();
    }

}