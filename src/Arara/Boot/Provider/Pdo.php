<?php

namespace Arara\Boot\Provider;

use Arara\Boot\Bootstrap,
    Arara\Boot\Config;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.3.0
 */
class Pdo implements Provider
{
    
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param  \Arara\Boot\Config $config
     * @param  \Arara\Boot\Bootstrap $bootstrap
     * @throws \OutOfBoundsException
     */
    public function __construct(Config $config, Bootstrap $bootstrap)
    {
        switch ($config->count()) {
            case 1:
                $pdo = new \PDO($config->get('dsn'));
                break;
            case 2:
                $pdo = new \PDO($config->get('dsn'), $config->get('usename'));
                break;
            case 3:
                $pdo = new \PDO($config->get('dsn'), $config->get('usename'), $config->get('password'));
                break;
            case 4:
                $pdo = new \PDO(
                    $config->get('dsn'), 
                    $config->get('usename'), 
                    $config->get('password'), 
                    $config->get('options')->toArray()
                );
                break;
            default:
                $message = 'Unable to create PDO object with given data';
                throw new \InvalidArgumentException($message);
        }
        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function __invoke()
    {
        return $this->pdo;
    }

}

