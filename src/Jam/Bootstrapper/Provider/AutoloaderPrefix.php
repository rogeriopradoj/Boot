<?php

namespace Jam\Bootstrapper\Provider;

use Jam\Bootstrapper\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
class AutoloaderPrefix implements Provider
{

    /**
     * @var array
     */
    private $prefixes = array();

    /**
     * @param array $config
     * @param \Jam\Bootstrapper\Bootstrap $bootstrap
     */
    public function __construct(array $config, Bootstrap $bootstrap)
    {
        foreach ($config as $namespace  => $path) {
            $this->addPrefix($namespace, $path);
        }
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * @return \Jam\Bootstrapper\Provider\AutoloaderPrefix
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param  string $namespace
     * @param  string $path
     * @return \Jam\Bootstrapper\Provider\AutoloaderPrefix
     */
    public function addPrefix($namespace, $path)
    {
        $this->prefixes[$namespace] = $path;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * @param  string $className
     * @return void
     */
    public function loadClass($className)
    {
        foreach ($this->prefixes as $namespace  => $path) {
            if (false === strpos($className, $namespace)) {
                continue;
            }

            $separator  = false !== strpos($className, '\\') ? '\\' : '_';
            $filename   = str_replace($namespace, $path, $className) . '.php';
            $filename   = str_replace($separator, DIRECTORY_SEPARATOR, $filename);
            if (file_exists($filename)) {
                require_once $filename;

                return true;
            }
        }

        return false;
    }

}
