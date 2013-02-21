<?php

namespace Arara\Boot\Provider;

use Arara\Boot\Bootstrap;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.1.0
 */
class Php implements Provider
{

    /**
     * @param array $config
     * @param \Arara\Boot\Bootstrap $bootstrap
     */
    public function __construct(array $config, Bootstrap $bootstrap)
    {
        foreach ($config as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param  string $key
     * @param  mixed $value
     * @return \Arara\Boot\Provider\Php
     */
    public function set($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $subKey => $subValue) {
                $this->set($key . '.' . $subKey, $subValue);
            }
        } else {
            ini_set($key, $value);
        }

        return $this;
    }

    /**
     * @return \Arara\Boot\Provider\Php
     */
    public function __invoke()
    {
        return $this;
    }

}
