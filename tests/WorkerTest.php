<?php namespace Oven\TestCase;

use Oven\Worker;
use Mockery as m;

class WorkerTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testBuildWithNamespaceMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar/Foo')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->with('bar/Foo', 0755, true)
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('foobaz')
            ->andReturn(['source' => 'source-foobar']);

        $worker->build('foobar', 'foo', 'bar', ['foobaz'], ['force' => false, 'namespace' => true]);
    }

    public function testBuildIgnoreNamespaceMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar')
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('foobaz')
            ->andReturn(['source' => 'source-foobar']);

        $worker->build('foobar', 'foo', 'bar', ['foobaz'], ['force' => false, 'namespace' => false]);
    }

    public function testBuildWithAllIngredientsMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar/Foo')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->with('bar/Foo', 0755, true)
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('ingredients')
            ->andReturn(['foobaz']);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('foobaz')
            ->andReturn(['source' => 'source-foobaz']);

        $worker->build('foobar', 'foo', 'bar', [], ['force' => false, 'namespace' => true]);
    }

    /**
     * @expectedException \Oven\Exception\InvalidRecipeException
     */
    public function testBuildWithNoIngredientsFoundMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar/Foo')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->with('bar/Foo', 0755, true)
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('ingredients')
            ->andReturn(null);

        $worker->build('foobar', 'foo', 'bar', [], ['force' => false, 'namespace' => true]);
    }

    /**
     * @expectedException \Oven\Exception\InvalidRecipeException
     */
    public function testBuildWithMissingIngredientMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar/Foo')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->with('bar/Foo', 0755, true)
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('foobaz')
            ->andReturn(null);

        $worker->build('foobar', 'foo', 'bar', ['foobaz'], ['force' => false, 'namespace' => true]);
    }


    /**
     * @expectedException \Oven\Exception\InvalidRecipeException
     */
    public function testBuildWithMissingIngredientSourceMethod()
    {
        list($worker, $reader) = $this->getMocks();
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');

        $reader->shouldReceive('filesystem')
            ->once()
            ->andReturn($filesystem);

        $filesystem->shouldReceive('isDirectory')
            ->once()
            ->with('bar/Foo')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->once()
            ->with('bar/Foo', 0755, true)
            ->andReturn(true);

        $reader->shouldReceive('read')
            ->once()
            ->with('foobar')
            ->andReturn($reader);

        $reader->shouldReceive('getItem')
            ->once()
            ->with('foobaz')
            ->andReturn([]);

        $worker->build('foobar', 'foo', 'bar', ['foobaz'], ['force' => false, 'namespace' => true]);
    }

    protected function getMocks()
    {
        $reader = m::mock('Oven\Recipe\Reader');
        $worker = new Worker($reader);

        return [$worker, $reader];
    }

}