<?php

require_once __DIR__ . '/../bootstrap.php';

$config      = array('title' => 'Jam\Bootstrapper');
$environment = 'demo';
$bootstrap   = new Jam\Bootstrapper\Bootstrap($config, $environment);
$bootstrap->run(function ($bootstrap) {
    echo 'Do what you want with ' . $bootstrap->getConfig()['title'] . PHP_EOL;
});