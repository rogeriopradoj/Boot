<?php

namespace Jam\Bootstrapper\Provider;

use Jam\Bootstrapper\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
class Php implements Provider
{

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     * @param \Jam\Bootstrapper\Bootstrap $bootstrap
     */
    public function __construct(array $config, Bootstrap $bootstrap)
    {
        foreach ($config as $key => $value) {
            if (!is_array($value)) {
                ini_set($key, $value);
                continue;
            }
            foreach ($value as $valueKey => $valueData) {
                ini_set($key . '.' . $valueKey, $valueData);
            }
        }
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->config;
    }

}
