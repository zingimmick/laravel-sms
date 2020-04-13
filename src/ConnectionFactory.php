<?php


namespace Zing\LaravelSms;


use Zing\LaravelSms\Exceptions\InvalidArgumentException;

class ConnectionFactory
{
    /**
     * @param array $config
     * @return \Zing\LaravelSms\Contracts\Driver
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     */
    public function make(array $config)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }
        $driver = $config['driver'];
        if (class_exists($driver)) {
            return new $driver($config);
        }
        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}