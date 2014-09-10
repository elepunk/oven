<?php namespace Oven\TestCase\Recipe;

use Mockery as m;
use Oven\Recipe\Configurator;

class ConfiguratorTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testSetupMethod()
    {
        list($configurator, $filesystem) = $this->getMocks();

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->andReturn(true);

        $filesystem->shouldReceive('put')
            ->twice()
            ->andReturn(1);

        $configurator->setup(__DIR__.'/../stub');
    }

    public function testGetRecipePathMethod()
    {
        list($configurator, $filesystem) = $this->getMocks();
        $content = [
            'recipe_path' => 'foo/bar'
        ];

        $filesystem->shouldReceive('get')
            ->once()
            ->andReturn(json_encode($content));

        $this->assertEquals('foo/bar', $configurator->getRecipePath());
    }

    protected function getMocks()
    {
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');
        $configurator = new Configurator($filesystem);

        return [$configurator, $filesystem];
    }

}