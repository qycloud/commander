<?php
namespace Commander;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallbackCommand extends Command
{
    private $_callback;

    public function __construct($name, $callback)
    {
        $this->_callback = $callback;
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $callback = $this->_callback;
        $callback($input, $output);
    }
}