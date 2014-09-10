<?php namespace Oven\TestCase\Command;

use Mockery as m;
use Oven\Command\CommandTrait;

class CommandTraitTest extends \PHPUnit_Framework_TestCase {

    private $trait;

    private $input;

    private $output;

    public function setUp()
    {
        $this->input = m::mock('Symfony\Component\Console\Input\InputInterface');
        $this->output = m::mock('Symfony\Component\Console\Output\OutputInterface');

        $this->trait = new CommandTraitStub($this->input, $this->output);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testExecuteMethod()
    {
        $this->trait->execute($this->input, $this->output);
    }

    public function testArgumentMethod()
    {
        $this->input->shouldReceive('getArgument')
            ->once()
            ->andReturn('foo');

        $this->trait->argument('foo');
    }

    public function testOptionMethod()
    {
        $this->input->shouldReceive('getOption')
            ->once()
            ->andReturn('foo');

        $this->trait->option('foo');
    }

    public function testSayMethod()
    {
        $this->output->shouldReceive('write')
            ->once();

        $this->trait->say('input', 'foobar');
    }

}

class CommandTraitStub {

    use CommandTrait;

    protected $input;

    protected $output;

    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function fire()
    {

    }

}