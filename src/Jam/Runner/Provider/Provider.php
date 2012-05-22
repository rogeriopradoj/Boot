<?php

namespace Jam\Runner\Provider;

/**
 * @category   Jam
 * @package    Jam\Runner
 * @subpackage Jam\Runner\Provider
 * @author     Henrique Moody <henriquemoody@gmail.com>
 * @since      0.0.1
 */
interface Provider
{

    /**
     * @param    \Jam\Config\Ini $config
     * @return   \Jam\Runner\Provider\Provider
     */
    public function init(\Jam\Config\Ini $config);

    /**
     * @return  mixed
     */
    public function get();


}

