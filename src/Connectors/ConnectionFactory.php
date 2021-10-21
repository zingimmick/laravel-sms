<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Connectors;

class ConnectionFactory
{
    public function make(array $config): Connector
    {
        return (new Connector($config))->connect($config);
    }
}
