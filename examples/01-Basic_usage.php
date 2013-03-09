<?php

require_once __DIR__ . '/../bootstrap.php';

$config      = array('title' => 'Arara\Boot');
$environment = 'demo';
$bootstrap   = new Arara\Boot\Bootstrap($config, $environment, __DIR__);
$bootstrap->run(function ($bootstrap) {
    echo 'Do what you want with ' . $bootstrap->getConfig()['title'] . PHP_EOL;
});
