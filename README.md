# Arara/Boot
[![Build Status](https://secure.travis-ci.org/Arara/Boot.png)](http://travis-ci.org/Arara/Boot)

Just a simple application bootstrapper.

## Installation

Packages available on [Packagist](https://packagist.org/packages/Arara/Boot).
Autoloading with composer is PSR-0 compatible.

## Usage

### Basic usage

````php
$config      = array('title' => 'Arara\Boot');
$environment = 'demo';
$bootstrap   = new Arara\Boot\Bootstrap($config, $environment);
$bootstrap->run(function ($bootstrap) {
    echo 'Do what you want with ' . $bootstrap->getConfig()['title'] . PHP_EOL;
});
````

### Make the way you like

````php
$config = parse_ini_file('settings.ini');
$environment = 'demo';
$bootstrap   = new Arara\Boot\Bootstrap($config, $environment);
$bootstrap->run(function ($bootstrap) {
    $content = '';
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $pdo = new PDO($bootstrap->getConfig()['db_dns']);
            $pdo->exec(
                'CREATE TABLE IF NOT EXISTS emails (
                    id INTEGER PRIMARY KEY ASC,
                    created INTEGER NOT NULL,
                    email TEXT NOT NULL
                );'
            );
            $insert = $pdo->prepare('INSERT INTO "emails" ("created", "email") VALUES (?, ?)');
            if ($insert->execute(array(time(), $_POST['email']))) {
                $content = 'E-mail successfully saved';
            } else {
                $content = 'Failure when saving e-mail';
                if ($bootstrap->getEnvironment() != 'production') {
                    $content .= '<br />';
                    $content .= '<pre>' . print_r($pdo->errorInfo(), true) . '</pre>';
                }
            }
            break;

        case 'GET':
            $content = '<form action="" method="POST">
                            Type your e-mail here: <input type="text" name="email" />
                            <input type="submit" />
                        </form>';
            break;

        default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
            $content = 'Unable to process ' . $_SERVER['REQUEST_METHOD'];
    }

    echo $content;
});
````

## Providers

Providers are classes that are loaded based on the name of a configuration key.
So, if you have a config like ``array('foo' => array(/* ... */)`` the provider
loader will try to load a provider called by **foo**.

For now we have following providers:

* autoloaderPrefix: Autoloader of classes (not PSR-0);
* php: Defines INI directives.

### autoloaderPrefix

Arbitrary autoloader for classes.

#### Example

````
user@host [/path/of/application/models] $ tree
.
└── models
    └── Foo
        └── Bar.php

2 directories, 1 file
````

In this case ``/path/of/application/models/Foo/Bat.php`` contains the class
``Application_Model_Foo_Bar``, in this case to register this path on autoloader
you can use the following configuration:

````php
$bootstrap = new Arara\Boot\Bootstrap(
    array(
        'autoloaderPrefix' => array(
            'Application_Model' => '/path/of/application/models',
        )
    ),
    getenv('ENVIRONMENT') ?: 'dev'
);
````

We are using ``_`` as namespace saparator in this example, but you can use ``\``
as namespace separator with no problem.

### php

This provider can be used to change INI directives.

#### Example

````php
$environment = getenv('ENVIRONMENT') ?: 'dev';
$config      = array(
    'php' => array(
        'error_reporting' => E_ALL | E_STRICT,
        'date' => array(
            'timezone' => 'America/Sao_Paulo',
        )
    )
);
if ($environment == 'dev') {
    $config['php']['display_errors'] = true;
} else {
    $config['php']['display_errors'] = false;
}

$bootstrap   = new Arara\Boot\Bootstrap($config, $environment);
$bootstrap->run();

// America/Sao_Paulo
echo ini_get('date.timezone') . PHP_EOL;

// dev environment should displays erros or
// SOME environment should not displays erros
echo $bootstrap->getEnvironment() . ' environment should ' .
     (ini_get('display_errors')? '' : 'not ') . 'displays erros' . PHP_EOL;
````

Note that ``date`` is an array that will be replaced by the the correct
directives recursively.
