<?php namespace Oven\TestCase\Command;

use Mockery as m;
use Oven\Command\ConfigureCommand;

class ConfigureCommandTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testFireMethod()
    {
        $command = new ConfigureCommand;
        $mockInput = m::mock('Symfony\Component\Console\Input\InputInterface');
        $mockOutput = m::mock('Symfony\Component\Console\Ouput\OutputInterface');
        $command->input = $mockInput;
        $command->output = $mockOutput;

        $mockInput->shouldReceive('getArgument')
            ->once();

        $mockOutput->shouldReceive('write')
            ->once();

        $command->fire();
    }

}
