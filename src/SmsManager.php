<?php

namespace Zing\LaravelSms;
use GrahamCampbell\Manager\AbstractManager;

/**
 * Class SmsManager.
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