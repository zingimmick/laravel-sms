<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Connectors;

class ConnectionFactory
{
    /**
     * @param array<string, mixed> $config
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     * @return \Zing\LaravelSms\Connectors\Connector
     */
    public function make(array $config): Connector
    {
        return (new Connector($config))->connect($config);
    }
}
