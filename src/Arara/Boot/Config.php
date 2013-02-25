<?php

namespace Arara\Boot;

/**
 * @author  Henrique Moody <henriquemoody@gmail.com>
 * @since   0.2.0
 */
class Config implements \Countable, \Iterator
{

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @see get
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param  string $key
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function get($key)
    {
        if (false === $this->has($key)) {
            $message = sprintf('Property "%s" was not found', $key);
            throw new \OutOfBoundsException($message);
        }

        if (is_array($this->data[$key])) {
            return new self($this->data[$key]);
        }

        return $this->data[$key];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        if ($this->has($this->key())) {
            return $this->get($this->key());
        }
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $return = next($this->data);

        if (true === $this->has($this->key())) {
            $return = $this->get($this->key());
        }

        return $return;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * @return mixed
     */
    public function valid()
    {
        return $this->has($this->key());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

}
