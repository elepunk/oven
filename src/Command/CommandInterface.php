<?php namespace Oven\Command;

interface CommandInterface {

    /**
     * Execute command process
     *
     * @return void
     */
    public function fire();

}
