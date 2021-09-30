<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Connectors;

class ConnectionFactory
{
    /**
     * @return \Zing\LaravelSms\Connectors\Connector
     */
    public function make(array $config)
    {
        return (new Connector($config))->connect($config);
    }
}
