<?php

/**
 * @namespace
 */
namespace Procman\Runner\Provider;

/**
 * @category   Procman
 * @package    Procman\Runner
 * @subpackage Procman\Runner\Provider
 * @author     Henrique Moody <henriquemoody@gmail.com>
 * @since      0.0.1
 */
interface Provider
{

    /**
     * @param    \Jam\Config\AbstractConfig $config
     * @return   \Procman\Runner\Provider\Provider
     */
    public function init(\Jam\Config\AbstractConfig $config);

    /**
     * @return  mixed
     */
    public function get();


}

