<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use Zing\LaravelSms\Connectors\ConnectionFactory;

/**
 * @method bool send($number, $message) 发送消息
 * @method \Zing\LaravelSms\Connectors\Connector connection(string $name = null)
 */
class SmsManager extends AbstractManager
{
    public function __construct(
        Repository $config,
        protected ConnectionFactory $connectionFactory
    ) {
        parent::__construct($config);
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function createConnection(array $config): Connectors\Connector
    {
        return $this->connectionFactory->make($config);
    }

    protected function getConfigName(): string
    {
        return 'sms';
    }

    public function via(?string $name = null): Connectors\Connector
    {
        return $this->connection($name);
    }
}
