<?php
use Commander;
use Symfony\Component\Console\Application;

class Commander
{
    private $_application;

    public function __construct()
    {
        $this->_application = new Application();
    }

    public function name($name)
    {
    }

    public function version($version)
    {
    }

    public function option()
    {
    }

    public function command()
    {
    }

    public function run()
    {
        $this->_application->run();
    }
}
