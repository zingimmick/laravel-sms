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

/**
 * @internal
 */
final class DriverTest extends TestCase
{
    public function testException(): void
    {
        $phoneNumber = new PhoneNumber(18_188_888_888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new Exception('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        (new Connector([]))->send($phoneNumber, $message);
    }

    public function testStaticException(): void
    {
        $phoneNumber = new PhoneNumber(18_188_888_888);
        $message = new Message([]);
        Event::fake();
        Event::shouldReceive('dispatch')->andThrow(new CouldNotSendNotification('test'));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('test');
        (new Connector([]))->send($phoneNumber, $message);
    }
}
