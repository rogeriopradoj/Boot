<?php

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI == 'cli') {
    $descriptorSpec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w"),
    );

    $host = 'localhost:8484';
    $command = PHP_BINARY . ' -S ' . escapeshellarg($host) . ' '  . escapeshellarg(__FILE__);
    $process = proc_open($command, $descriptorSpec, $pipes);
    if (!is_resource($process)) {
        fwrite(STDERR, 'Unable to run: ' . $command . PHP_EOL);
        exit(1);
    }
    fwrite(STDOUT, 'Running server on ' . $host . PHP_EOL);
    fwrite(STDOUT, 'Press Ctrl-C to quit' . PHP_EOL);
    exit(0);
}

$config = array(
    'database' => array(
        'dns' => sprintf('sqlite:%s%s02-It_your_way.sq3', sys_get_temp_dir(), DIRECTORY_SEPARATOR)
    )
);
$environment = 'demo';
$bootstrap   = new Arara\Boot\Bootstrap($config, $environment);
$bootstrap->run(function ($bootstrap) {
    $content = '';
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $pdo = new PDO($bootstrap->getConfig()['database']['dns']);
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