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
class Php implements Provider
{

    /**
     * @var \Jam\Config\Arr
     */
    private $_config;

    /**
     * @return \Jam\Config\Arr
     */
    public function get()
    {
        return $this->_config;
    }

    /**
     * @param   \Jam\Config\Arr $config
     * @return  \Jam\Runner\Provider\Php
     */
    public function init(\Jam\Config\Arr $config)
    {
        foreach ($config as $key => $value) {
            if (!$value instanceof \Jam\Config\Arr) {
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

