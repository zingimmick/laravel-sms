<?php

namespace Zing\LaravelSms\Connectors;

use Zing\LaravelSms\Contracts\Driver;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;

class ConnectionFactory
{
    /**
     * @param array $config
     *
     * @return \Zing\LaravelSms\Contracts\Driver
     *
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     */
    public function make(array $config)
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }
        $driver = $config['driver'];
        if (class_exists($driver) && in_array(Driver::class, class_implements($driver), true)) {
            return new $driver($config);
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}
