<?php namespace Oven\TestCase;

use Mockery as m;
use Oven\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testRunMethod()
    {
        $configurator = m::mock('Oven\Recipe\Configurator');
        $command = m::mock('Oven\Command\ConfigureCommand');

        $factory = new Factory($command, $configurator);

        $command->shouldReceive('argument')
            ->once()
            ->with('path')
            ->andReturn($path = '/foo/bar');

        $configurator->shouldReceive('setup')
            ->once()
            ->with($path);

        $command->shouldReceive('say')
            ->once()
            ->with("info", "Your recipe path is now set to {$path}");

        $factory->run();
    }

}