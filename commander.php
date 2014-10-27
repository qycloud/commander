<?php
use Commander\Option;
use Commander\Argument;
use Commander\CallbackCommand;
use Symfony\Component\Console\Application as Application;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Commander
{
    const ARGUMENT_REQUIRED = Argument::REQUIRED;
    const ARGUMENT_OPTIONAL = Argument::OPTIONAL;

    const OPTION_VALUE_NONE = Option::VALUE_NONE;
    const OPTION_VALUE_REQUIRED = Option::VALUE_REQUIRED;
    const OPTION_VALUE_OPTIONAL = Option::VALUE_OPTIONAL;
    const OPTION_VALUE_IS_ARRAY = Option::VALUE_IS_ARRAY;

    private $_application;

    private function _getArrayValueByKey($array, $key, $default = null)
    {
        return (array_key_exists($key, $array) ? $array[$key] : $default);
    }

    public function __construct()
    {
        $this->_application = new Application();
    }

    public function name($name)
    {
        $this->_application->setName($name);
        return $this;
    }

    public function version($version)
    {
        $this->_application->setVersion($version);
        return $this;
    }

    public function command($option)
    {
        if ($option instanceof SymfonyCommand) {
            $this->_application->add($option);
            return $this;
        }

        $command = new CallbackCommand($option['name'], $option['callback']);
        $command->setDescription(
            $this->_getArrayValueByKey($option, 'description', $option['name'])
        );

        if (!empty($option['help'])) {
            $command->setHelp($option['help']);
        }

        if (!empty($option['arguments']) && is_array($option['arguments'])) {
            $validArgumentModes = array(static::ARGUMENT_OPTIONAL, static::ARGUMENT_REQUIRED);
            foreach ($option['arguments'] as $argument) {
                if (isset($argument['mode']) && in_array($argument['mode'], $validArgumentModes)) {
                    $mode = $argument['mode'];
                } else {
                    $mode = static::ARGUMENT_OPTIONAL;
                }

                if (!empty($argument['default']) && $mode === static::ARGUMENT_OPTIONAL) {
                    $default = $argument['default'];
                } else {
                    $default = null;
                }

                $description = $this->_getArrayValueByKey($argument, 'description', $argument['name']);
                $command->addArgument($argument['name'], $mode, $description, $default);
            }
        }

        if (!empty($option['options']) && is_array($option['options'])) {
            $validOptionModes = array(
                static::OPTION_VALUE_NONE,
                static::OPTION_VALUE_REQUIRED,
                static::OPTION_VALUE_OPTIONAL,
                static::OPTION_VALUE_IS_ARRAY
            );

            foreach ($option['options'] as $argumentOption) {
                if (isset($argumentOption['mode']) && in_array($argumentOption['mode'], $validOptionModes)) {
                    $mode = $argumentOption['mode'];
                } else {
                    $mode = static::OPTION_VALUE_OPTIONAL;
                }

                if (in_array($mode, array(static::OPTION_VALUE_REQUIRED, static::OPTION_VALUE_NONE))) {
                    $default = null;
                } else if (!empty($argumentOption['default'])) {
                    $default = $argumentOption['default'];
                } else {
                    $default = null;
                }

                $shortcut = $this->_getArrayValueByKey($argumentOption, 'shortcut');
                $description = $this->_getArrayValueByKey(
                    $argumentOption,
                    'description',
                    str_replace('-', '', $argumentOption['name'])
                );
                $command->addOption($argumentOption['name'], $shortcut, $mode, $description, $default);
            }
        }

        $this->_application->add($command);
        return $this;
    }

    public function run()
    {
        $this->_application->run();
    }
}
