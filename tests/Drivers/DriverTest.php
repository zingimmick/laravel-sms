<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Exception;
use Mockery;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Tests\TestCase;

class DriverTest extends TestCase
{
    public function test_exception()
    {
        $number = new PhoneNumber(18188888888);
        $message = new \Overtrue\EasySms\Message([]);
        $driver = Mockery::mock(Connector::class . '[sending]', [[]]);
        $driver->shouldReceive('sending')->andThrow(new Exception('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        $driver->send($number, $message);
    }

    public function test_static_exception()
    {
        $number = new PhoneNumber(18188888888);
        $message = new \Overtrue\EasySms\Message([]);
        $driver = Mockery::mock(Connector::class . '[sending]', [[]]);
        $driver->shouldReceive('sending')->andThrow(new CouldNotSendNotification('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        $driver->send($number, $message);
    }
}

class DummyDriver extends Gateway
{
    public function send(PhoneNumberInterface $number, MessageInterface $message, Config $config)
    {
    }
}
