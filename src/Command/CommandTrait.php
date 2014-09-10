<?php namespace Oven\Command;

/**
 * This file is copied/based from Laravel/Envoy package
 * which is released under MIT license
 *
 * @see  https://github.com/laravel/envoy
 */

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait CommandTrait {

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->fire();
    }

    /**
     * Get an argument from the input.
     *
     * @param  string  $key
     * @return string
     */
    public function argument($key)
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get an option from the input.
     *
     * @param  string  $key
     * @return string
     */
    public function option($key)
    {
        return $this->input->getOption($key);
    }

    /**
     * Output message to console
     *
     * @param string $type
     * @param string $message
     * @return string
     */
    public function say($type, $message)
    {
        $message = "<{$type}>{$message}</{$type}>";

        return $this->output->write($message.PHP_EOL);
    }

}