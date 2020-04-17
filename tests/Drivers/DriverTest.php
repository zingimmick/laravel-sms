<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Mockery;
use Zing\LaravelSms\Contracts\Message as MessageContract;
use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;
use Zing\LaravelSms\Drivers\Driver;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Tests\TestCase;

class DriverTest extends TestCase
{
    public function test_exception()
    {
        $number = new PhoneNumber(18188888888);
        $message = Message::text('【test】This is a test message.');
        $driver = Mockery::mock(DummyDriver::class . '[sendFormatted]', []);
        $driver->shouldReceive('sendFormatted')->andThrow(new \Exception('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        $driver->send($number, $message);
    }
}

class DummyDriver extends Driver
{
    public function sendFormatted(PhoneNumberContract $number, MessageContract $message)
    {
    }
}
