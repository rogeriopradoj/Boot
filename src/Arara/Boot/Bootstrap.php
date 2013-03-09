<?php

namespace Arara\Boot;

use Arara\Boot\Provider\Loader;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
class Bootstrap
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var \Arara\Boot\Provider\Loader
     */
    private $providerLoader;

    /**
     * @param string $config
     * @param string $environment
     */
    public function __construct(array $config, $environment, $rootDirectory)
    {
        $this->config = new Config($config);
        $this->environment = $environment;

        if (!(is_string($rootDirectory) && is_dir($rootDirectory))) {
            $message = sprintf('"%s" is not a valid directory', print_r($rootDirectory, true));
            throw new \InvalidArgumentException($message);
        }
        $this->rootDirectory = realpath($rootDirectory);
    }

    /**
     * @return \Arara\Boot\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @return \Arara\Boot\Provider\Loader
     */
    public function getProviderLoader()
    {
        if (!$this->providerLoader instanceof Loader) {
            $this->providerLoader = new Loader();
        }

        return $this->providerLoader;
    }

    /**
     * @param  string $name
     * @return \Arara\Boot\Provider\Provider
     */
    public function getProvider($name)
    {
        return $this->getProviderLoader()->get($name);
    }

    /**
     * @param  callable[optional] $callback
     * @return mixed
     */
    public function run($callback = null)
    {
        $callback = $callback ?: array($this->getProviderLoader(), 'load');
        if (!is_callable($callback)) {
            $message = sprintf('"%s" is not a valid callable', print_r($callback, true));
            throw new \InvalidArgumentException($message);
        }

        return call_user_func($callback, $this);
    }

}
