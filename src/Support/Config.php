<?php

namespace Zing\LaravelSms\Support;

use ArrayAccess;

/**
 * Class Config.
 */
class Config implements ArrayAccess
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $config = $this->config;

        if (isset($config[$key])) {
            return $config[$key];
        }

        if (strpos($key, '.') === false) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (! is_array($config) || ! array_key_exists($segment, $config)) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->config);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (isset($this->config[$offset])) {
            $this->config[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        if (isset($this->config[$offset])) {
            unset($this->config[$offset]);
        }
    }
}
