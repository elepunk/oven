<?php namespace Oven\TestCase\Recipe;

use Mockery as m;
use Oven\Recipe\Ingredient;

class IngredientTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testGetIngredientMethod()
    {
        list($ingredient, $fluent) = $this->getMocks();

        $this->assertInstanceOf('Illuminate\Support\Fluent', $ingredient->getIngredient());
    }

    public function testGetSourceDirMethod()
    {
        list($ingredient, $fluent) = $this->getMocks();

        $this->assertEquals('Foo', $ingredient->getSourceDir());
    }

    public function testIsEmptyDirMethod()
    {
        list($ingredient, $fluent) = $this->getMocks();

        $this->assertFalse($ingredient->isEmptyDir());

        $ingredientSource = $ingredient->getIngredient();
        $ingredientSource->offsetSet('empty-dir', true);

        $this->assertTrue($ingredient->isEmptyDir());

        $ingredientSource->offsetUnset('empty-dir');

        $this->assertFalse($ingredient->isEmptyDir());
    }

    protected function getMocks()
    {
        $fluent = m::mock('Illuminate\Support\Fluent');

        $stub = [
            'source' => 'Foo/Foobar.php',
            'name' => '(entity)Foobar.php',
            'empty-dir' => false
        ];

        $ingredient = new Ingredient($stub);

        return [$ingredient, $fluent];
    }

}