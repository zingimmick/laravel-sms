<?php

namespace Zing\LaravelSms\Connectors;

class ConnectionFactory
{
    /**
     * @param array $config
     *
     * @return \Zing\LaravelSms\Connectors\Connector
     *
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     */
    public function make(array $config)
    {
        return (new Connector($config))->connect($config);
    }
}
