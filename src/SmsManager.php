<?php

namespace Zing\LaravelSms;
use GrahamCampbell\Manager\AbstractManager;

/**
 * @method bool send($number, $message) 发送消息
 * @method \Zing\LaravelSms\Contracts\Driver connection(string $name = null)
 */
class SmsManager extends AbstractManager
{
    protected function createConnection(array $config)
    {
        $driver = $config['driver'];

        return new $driver($config);
    }

    protected function getConfigName()
    {
        return 'sms';
    }
}