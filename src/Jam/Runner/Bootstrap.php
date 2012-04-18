<?php

/**
 * @namespace
 */
namespace Jam\Runner;

use Jam\Config\Ini,
    Jam\Runner\Provider\Provider;

/**
 * @category   Jam
 * @package    Jam\Runner
 * @author     Henrique Moody <henriquemoody@gmail.com>
 * @since      0.0.1
 */
class Bootstrap
{

    /**
     * @var array
     */
    private $_providers = array();

    /**
     * @var array
     */
    private $_providerNamespaces = array();

    /**
     * @var \Jam\Config\AbstractConfig
     */
    private $_config;

    /**
     * @var string
     */
    private $_environment;

    /**
     * @param   string $config
     * @param   string $environment
     */
    public function __construct($config, $environment)
    {
        $this->_config      = new Ini($config, $environment);
        $this->_environment = $environment;
        $this->addProviderNamespace(__NAMESPACE__ . '\\Provider\\');
    }

    /**
     * @return  \Jam\Config\AbstractConfig
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @return  string
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /**
     * @return  array
     */
    public function getProviders()
    {
        return $this->_providers;
    }

    /**
     * @return  array
     */
    public function getProviderNamespaces()
    {
        return array_reverse($this->_providerNamespaces);
    }


    /**
     * @param   string $namespace
     * @return  \Jam\Runner\Bootstrap
     */
    public function addProviderNamespace($namespace)
    {
        $this->_providerNamespaces[] = $namespace;
        return $this;
    }

    /**
    * @param   string $name
    * @return  mixed
    */
    public function getProvider($name)
    {
        if ($this->hasProvider($name)) {
            return $this->_providers[$name];
        }
    }

    /**
    * @param   string $name
    * @return  bool
    */
    public function hasProvider($name)
    {
        return isset($this->_providers[$name]);
    }

    /**
     * @throws  \OutOfRangeException
     * @throws  \InvalidArgumentException
     * @return  \Jam\Runner\Bootstrap
     */
    public function loadProviders()
    {
        foreach ($this->getConfig() as $key => $config) {

            $provider = null;
            foreach ($this->getProviderNamespaces() as $namespace) {
                $className = $namespace . ucfirst($key);
                if (class_exists($className)) {
                    $provider = new $className();
                    break;
                }
            }

            if (null === $provider) {
                $message = sprintf('There is no provider called by "%s"', $key);
                throw new \OutOfRangeException($message);
            }

            if (!$provider instanceof Provider) {
                $message = '"%s" is not a valid provider class';
                $message = sprintf($message, get_class($provider));
                throw new \InvalidArgumentException($message);
            }

            $this->_providers[$key] = $provider->init($config)->get();
        }
        return $this;
    }

    /**
     * @param   callable[optional] $callback
     * @return  mixed
     */
    public function run($callback = null)
    {
        $callback = $callback ?: array($this, 'loadProviders');
        if (!is_callable($callback)) {
            $message = '"%s" is not a valid callable';
            $message = sprintf($message, var_export($callback, true));
            throw new \InvalidArgumentException($message);
        }
        return call_user_func($callback, $this);
    }


}

