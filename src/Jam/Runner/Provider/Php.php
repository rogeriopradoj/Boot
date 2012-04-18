<?php

/**
 * @namespace
 */
namespace Procman\Runner\Provider;

/**
 * @category   Procman
 * @package    Procman\Runner
 * @subpackage Procman\Runner\Provider
 * @uses       Procman\Runner\Provider\Provider
 * @author     Henrique Moody <henriquemoody@gmail.com>
 * @since      0.0.1
 */
class Php implements Provider
{

    /**
     * @var \Jam\Config\AbstractConfig
     */
    private $_config;

    /**
     * @return \Jam\Config\AbstractConfig
     */
    public function get()
    {
        return $this->_config;
    }

    /**
     * @param   \Jam\Config\AbstractConfig $config
     * @return  \Procman\Runner\Provider\Php
     */
    public function init(\Jam\Config\AbstractConfig $config)
    {
        foreach ($config as $key => $value) {
            if (!$value instanceof \Jam\Config\AbstractConfig) {
                ini_set($key, $value);
                continue;
            }
            foreach ($value as $valueKey => $valueData) {
                ini_set($key . '.' . $valueKey, $valueData);
            }
        }
        $this->_config = $config;
        return $this;
    }


}

