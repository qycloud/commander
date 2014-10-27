### Commander:

Commander is command line interfaces wraps [symfony/Console](https://github.com/symfony/Console), it makes easy to build command line application because developers don't have to create any command class files.

### Getting Started:

* Create composer.json file in root directory of  your application:

```json
 {
    "require": {
        "php": ">=5.4.0",
        "nanjingboy/commander": "*"
    }
}
```
* Install it via [composer](https://getcomposer.org/doc/00-intro.md)


### Usage Example:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Commander\Command;

class RenameCommand extends Command
{
    public function configure()
    {
        $this->setName('rename')
            ->setDescription('rename database')
            ->addArgument('new-name', Commander::ARGUMENT_REQUIRED, 'new name of the database')
            ->addArgument('current-name', Commander::ARGUMENT_REQUIRED, 'current name of the database');
    }

    public function execute($input, $output)
    {
        $output->writeln(
            '<info>rename database: </info>' . $input->getArgument('current-name') .
            '<info> with </info>' . $input->getArgument('new-name')
        );
    }
}

$commander = new Commander();
$commander->name('db')
    ->version('0.1.0')
    ->command(
        array(
            'name' => 'create',
            'description' => 'create database if not exists',
            'options' => array(
                array(
                    'name' => "dbName",
                    'mode' => Commander::OPTION_VALUE_REQUIRED
                )
            ),
            'callback' => function($input, $output) {
                $output->writeln('<info>create database: </info>' . $input->getOption('dbName'));
            }
        )
    )
    ->command(
        array(
            'name' => 'drop',
            'description' => 'drop database if exists',
            'arguments' => array(
                array(
                    'name' => 'dbName',
                    'mode' => Commander::ARGUMENT_REQUIRED
                )
            ),
            'callback' => function($input, $output) {
                $output->writeln('<info>drop database: </info>' . $input->getArgument('dbName'));
            }
        )
    )->command(new RenameCommand())
    ->run();
```

### Argument of Commander#command:

* Defined as a subclass of Commander\Command
* Defined as a array:

```php
array(
    'name' => string,
    'description' => string, // can ignore
    'help' => string, // can ignore
    'arguments' = array(
        array(
            'name' => string,
            'description' => string, // can ignore
            'mode' => Commander::ARGUMENT_REQUIRED | Commander::ARGUMENT_OPTIONAL, // default Commander::CARGUMENT_OPTIONAL
            'default' => mixed // for Commander::ARGUMENT_OPTIONAL mode only
        )
    ), // can ignore
    'options' => array(
        array(
            'name' => string,
            'shortcut' => string, // can ignore
            'description' => string, // can ignore
            'mode' => Commander::OPTION_VALUE_NONE | Commander::OPTION_VALUE_REQUIRED | Commander::OPTION_VALUE_OPTIONAL | Commander::OPTION_VALUE_IS_ARRAY, // default Commander::OPTION_VALUE_OPTIONAL
            'default' => mixed // must be null for Commander::OPTION_VALUE_REQUIRED or Commander::OPTION_VALUE_NONE
        )
    ), // can ignore
    'callback' => function($input, $output) {

    }
)
```

### License:
MIT