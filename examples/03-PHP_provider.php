<?php

require_once __DIR__ . '/../bootstrap.php';

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
