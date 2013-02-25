<?php

namespace Arara\Boot\Provider;

use Arara\Boot\Bootstrap,
    Arara\Boot\Config;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
interface Provider
{

    /**
     * @param \Arara\Boot\Config $config
     * @param \Arara\Boot\Bootstrap $bootstrap
     */
    public function __construct(Config $config, Bootstrap $bootstrap);

    /**
     * @return mixed
     */
    public function __invoke();

}
