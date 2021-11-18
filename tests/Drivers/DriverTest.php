<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Exception;
use Illuminate\Support\Facades\Event;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Tests\TestCase;

class DriverTest extends TestCase
{
    public function testException(): void
    {
        $phoneNumber = new PhoneNumber(18188888888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new Exception('test message'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test message');
        (new Connector([]))->send($phoneNumber, $message);
    }

    public function testStaticException(): void
    {
        $phoneNumber = new PhoneNumber(18188888888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new CouldNotSendNotification('test message'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test message');
        (new Connector([]))->send($phoneNumber, $message);
    }
}
