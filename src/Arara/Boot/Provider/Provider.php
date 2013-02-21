<?php

namespace Arara\Boot\Provider;

use Arara\Boot\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
interface Provider
{

    /**
     * @param array $config
     * @param \Arara\Boot\Bootstrap $bootstrap
     */
    public function __construct(array $config, Bootstrap $bootstrap);

    /**
     * @return mixed
     */
    public function __invoke();

}
