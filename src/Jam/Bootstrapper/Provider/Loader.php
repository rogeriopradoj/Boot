<?php

namespace Jam\Bootstrapper\Provider;

use Jam\Bootstrapper\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
class Loader
{

    /**
     * @var array
     */
    private $providers = array();

    /**
     * @var array
     */
    private $namespaces = array();

    public function __construct()
    {
        $this->addNamespace(__NAMESPACE__ . '\\');
    }

    /**
     * @param  string $namespace
     * @return \Jam\Bootstrapper\Provider\Loader
     */
    public function addNamespace($namespace)
    {
        $this->namespaces[] = $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return array_reverse($this->namespaces);
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        if (false === $this->has($name)) {
            $message = sprintf('There is no defined provider called by "%s"', print_r($name, true));
            throw new \OutOfBoundsException($message);
        }

        return $this->providers[$name]();
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->providers[$name]);
    }

    /**
     * @throws UnexpectedValueException
     * @param  \Jam\Bootstrapper\Bootstrap $bootstrap
     * @return \Jam\Bootstrapper\Provider\Loader
     */
    public function load(Bootstrap $bootstrap)
    {
        foreach ($bootstrap->getConfig() as $key => $config) {

            $reflection = null;

            foreach ($this->getNamespaces() as $namespace) {
                $className = $namespace . ucfirst($key);
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    break;
                }
            }

            if (null === $reflection) {
                continue;
            }

            if (false === $reflection->implementsInterface('Jam\Bootstrapper\Provider\Provider')) {
                $message = sprintf('"%s" is not a valid provider class', $reflection->getName());
                throw new \UnexpectedValueException($message);
            }

            $this->providers[$key] = $reflection->newInstance($config, $bootstrap);
        }

        return $this;
    }

}
