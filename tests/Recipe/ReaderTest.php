<?php namespace Oven\TestCase\Recipe;

use Mockery as m;
use Oven\Recipe\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testReadMethod()
    {
        $recipeStub = __DIR__.'/../stub/recipe.json';
        list($reader, $filesystem) = $this->getMocks();

        $filesystem->shouldReceive('exists')
            ->once()
            ->with($recipeStub)
            ->andReturn(true);

        $filesystem->shouldReceive('get')
            ->once()
            ->with($recipeStub)
            ->andReturn(file_get_contents($recipeStub));

        $this->assertInstanceOf('Oven\Recipe\Reader', $reader->read($recipeStub));
    }

    /**
     * @expectedException \Oven\Exception\RecipeNotFoundException
     */
    public function testReadMethodThrowRecipeNotFoundException()
    {
        list($reader, $filesystem) = $this->getMocks();

        $filesystem->shouldReceive('exists')
            ->once()
            ->with('recipe.json')
            ->andReturn(false);

        $reader->read('recipe.json');
    }

    /**
     * @expectedException \Oven\Exception\InvalidRecipeException
     */
    public function testReadMethodThrowInvalidRecipeException()
    {
        $recipeStub = __DIR__.'/../stub/recipe.json';
        list($reader, $filesystem) = $this->getMocks();

        $filesystem->shouldReceive('exists')
            ->once()
            ->with($recipeStub)
            ->andReturn(true);

        $filesystem->shouldReceive('get')
            ->once()
            ->with($recipeStub)
            ->andReturn('foobar');

        $reader->read($recipeStub);
    }

    public function testGetContentMethod()
    {
        list($reader, $filesystem) = $this->getMocks();

        $this->assertEmpty($reader->getContent());

        $recipeStub = __DIR__.'/../stub/recipe.json';
        $content = file_get_contents($recipeStub);

        $filesystem->shouldReceive('exists')
            ->once()
            ->with($recipeStub)
            ->andReturn(true);

        $filesystem->shouldReceive('get')
            ->once()
            ->with($recipeStub)
            ->andReturn($content);

        $reader->read($recipeStub);

        $this->assertEquals(json_decode($content, true), $reader->getContent());
    }

    public function testGetItemMethod()
    {
        list($reader, $filesystem) = $this->getMocks();

        $recipeStub = __DIR__.'/../stub/recipe.json';
        $content = file_get_contents($recipeStub);

        $filesystem->shouldReceive('exists')
            ->once()
            ->with($recipeStub)
            ->andReturn(true);

        $filesystem->shouldReceive('get')
            ->once()
            ->with($recipeStub)
            ->andReturn($content);

        $reader->read($recipeStub);

        $this->assertEquals('Sample Recipe', $reader->getItem('name'));
        $this->assertNull($reader->getItem('foobar'));
    }

    public function testFilesystemMethod()
    {
        list($reader, $filesystem) = $this->getMocks();

        $this->assertInstanceOf('Illuminate\Filesystem\Filesystem', $reader->filesystem());
    }

    protected function getMocks()
    {
        $filesystem = m::mock('Illuminate\Filesystem\Filesystem');
        $reader = new Reader($filesystem);

        return [$reader, $filesystem];
    }

}
