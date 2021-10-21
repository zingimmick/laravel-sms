<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use Zing\LaravelSms\Connectors\ConnectionFactory;

/**
 * @method static bool send($number, $message) 发送消息
 */
class SmsManager extends AbstractManager
{
    /**
     * @var \Zing\LaravelSms\Connectors\ConnectionFactory
     */
    protected $factory;

    public function __construct(Repository $config, ConnectionFactory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    /**
     * @return object|\Zing\LaravelSms\Connectors\Connector
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    protected function getConfigName(): string
    {
        return 'sms';
    }

    public function via(?string $name = null): object
    {
        return $this->connection($name);
    }
}
