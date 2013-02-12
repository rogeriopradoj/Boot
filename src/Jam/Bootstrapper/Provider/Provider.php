<?php

namespace Jam\Bootstrapper\Provider;

use Jam\Bootstrapper\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
interface Provider
{

    /**
     * @param array $config
     * @param \Jam\Bootstrapper\Bootstrap $bootstrap
     */
    public function __construct(array $config, Bootstrap $bootstrap);

    /**
     * @return mixed
     */
    public function __invoke();

}
