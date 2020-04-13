<?php

namespace Zing\LaravelSms;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use Zing\LaravelSms\Connectors\ConnectionFactory;

/**
 * @method bool send($number, $message) 发送消息
 */
class SmsManager extends AbstractManager
{
    protected $factory;

    public function __construct(Repository $config, ConnectionFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    protected function getConfigName()
    {
        return 'sms';
    }
}
