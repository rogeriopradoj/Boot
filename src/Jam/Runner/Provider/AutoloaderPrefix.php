<?php

namespace Jam\Runner\Provider;

/**
 * @category   Jam
 * @package    Jam\Runner
 * @subpackage Jam\Runner\Provider
 * @uses       Jam\Runner\Provider\Provider
 * @author     Henrique Moody <henriquemoody@gmail.com>
 * @since      0.0.1
 */
class AutoloaderPrefix implements Provider
{

    /**
     * @var array
     */
    private $_prefixes = array();

    /**
     * @return  \Jam\Runner\Provider\AutoloaderPrefix
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param   string $namespace
     * @param   string $path
     * @return  \Jam\Runner\Provider\AutoloaderPrefix
     */
    public function addPrefix($namespace, $path)
    {
        $this->_prefixes[$namespace] = $path;
        return $this;
    }

    /**
     * @param   \Jam\Config\Arr $config
     * @return  \Jam\Runner\Provider\AutoloaderPrefix
     */
    public function init(\Jam\Config\Arr $config)
    {
        foreach ($config as $namespace  => $path) {
            $this->_prefixes[$namespace] = $path;
        }
        spl_autoload_register(array($this, 'loadClass'));
        return $this;
    }

    /**
     * @param   string $className
     * @return  boolean
     */
    public function loadClass($className)
    {
        foreach ($this->_prefixes as $namespace  => $path) {
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

