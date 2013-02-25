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
    private $providerLoader;

    /**
     * @param string $config
     * @param string $environment
     */
    public function __construct(array $config, $environment)
    {
        $this->config = new Config($config);
        $this->environment = $environment;
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
