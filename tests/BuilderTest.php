<?php namespace Oven\TestCase;

use Oven\Builder;
use Mockery as m;

class BuilderTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testRunWithRecipePathMethod()
    {
        $command = m::mock('Oven\Command\BakeCommand');
        $configurator = m::mock('Oven\Recipe\Configurator');
        $worker = m::mock('Oven\Worker');

        $builder = new Builder($command, $configurator, $worker);

        $command->shouldReceive('option')
            ->once()
            ->with('f')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->once()
            ->with('ignore-namespace')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->twice()
            ->with('recipe')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->once()
            ->with('output')
            ->andReturn('foo');

        $command->shouldReceive('option')
            ->twice()
            ->with('destination')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->twice()
            ->with('items')
            ->andReturn(['foo', 'bar']);

        $worker->shouldReceive('build')
            ->once();

        $builder->run();
    }

    public function testRunWithRecipeTemplateMethod()
    {
        $command = m::mock('Oven\Command\BakeCommand');
        $configurator = m::mock('Oven\Recipe\Configurator');
        $worker = m::mock('Oven\Worker');

        $builder = new Builder($command, $configurator, $worker);

        $command->shouldReceive('option')
            ->once()
            ->with('f')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->once()
            ->with('ignore-namespace')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->once()
            ->with('recipe')
            ->andReturn(null);

        $command->shouldReceive('argument')
            ->once()
            ->with('recipe-name')
            ->andReturn('foobar');

        $configurator->shouldReceive('getRecipePath')
            ->once()
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->once()
            ->with('output')
            ->andReturn('foo');

        $command->shouldReceive('option')
            ->twice()
            ->with('destination')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->twice()
            ->with('items')
            ->andReturn(['foo', 'bar']);

        $worker->shouldReceive('build')
            ->once();

        $builder->run();
    }

    public function testRunWithNullDestinationMethod()
    {
        $command = m::mock('Oven\Command\BakeCommand');
        $configurator = m::mock('Oven\Recipe\Configurator');
        $worker = m::mock('Oven\Worker');

        $builder = new Builder($command, $configurator, $worker);

        $command->shouldReceive('option')
            ->once()
            ->with('f')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->once()
            ->with('ignore-namespace')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->twice()
            ->with('recipe')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->once()
            ->with('output')
            ->andReturn('foo');

        $command->shouldReceive('option')
            ->once()
            ->with('destination')
            ->andReturn(null);

        $command->shouldReceive('argument')
            ->twice()
            ->with('items')
            ->andReturn(['foo', 'bar']);

        $worker->shouldReceive('build')
            ->once();

        $builder->run();
    }

    public function testRunWithEmptyArgumentMethod()
    {
        $command = m::mock('Oven\Command\BakeCommand');
        $configurator = m::mock('Oven\Recipe\Configurator');
        $worker = m::mock('Oven\Worker');

        $builder = new Builder($command, $configurator, $worker);

        $command->shouldReceive('option')
            ->once()
            ->with('f')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->once()
            ->with('ignore-namespace')
            ->andReturn(false);

        $command->shouldReceive('option')
            ->twice()
            ->with('recipe')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->once()
            ->with('output')
            ->andReturn('foo');

        $command->shouldReceive('option')
            ->twice()
            ->with('destination')
            ->andReturn('/foo/bar');

        $command->shouldReceive('argument')
            ->once()
            ->with('items')
            ->andReturn([]);

        $worker->shouldReceive('build')
            ->once();

        $builder->run();
    }

}