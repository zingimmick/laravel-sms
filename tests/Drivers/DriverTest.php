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
        $number = new PhoneNumber(18188888888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new Exception('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        (new Connector([]))->send($number, $message);
    }

    public function testStaticException(): void
    {
        $number = new PhoneNumber(18188888888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new CouldNotSendNotification('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        (new Connector([]))->send($number, $message);
    }
}
