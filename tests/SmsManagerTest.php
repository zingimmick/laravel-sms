<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Overtrue\EasySms\Message;
use RuntimeException;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Events\SmsSending;
use Zing\LaravelSms\Events\SmsSent;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsManager;

test(
    'send with default driver',
    function ($number, $message): void {
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        prepareLoggerExpectation()->with(sendString($number, $message));
        $sms->send($number, $message);
    }
)->with('numbers');
test(
    'send with log channel',
    function ($number, $message): void {
        $channel = 'test';
        config()->set('sms.connections.log.channel', $channel);
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        prepareLoggerExpectation($channel)->with(sendString($number, $message));
        $sms->send($number, $message);
    }
)->with('numbers');
test(
    'send with log level',
    function ($number, $message): void {
        $level = 'info';
        config()->set('sms.connections.log.level', $level);
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        prepareLoggerExpectation(null, $level)->with(sendString($number, $message));
        $sms->send($number, $message);
    }
)->with('numbers');

it(
    'can notify',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }
);

it(
    'can notify with alias',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[via]');
        $notification->shouldReceive('via')->andReturn(['sms']);
        prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }
);
it(
    'can notify with route',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        Notification::route(SmsChannel::class, '18888888888')->notify($notification);
    }
);

it(
    'can notify with route alias',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        Notification::route('sms', '18888888888')->notify($notification);
    }
);

it(
    'can notity with string',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn('This is a test message.');
        prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }
);

test(
    'notify with invalid receiver',
    function (): void {
        /** @var \Zing\LaravelSms\Tests\Phone $phone */
        $phone = Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
        $notification = new VerifyCode();
        $phone->shouldReceive('routeNotificationForSms')->once()->andReturn('');
        Log::shouldReceive()->never();
        assertTrue(true);
        $phone->notify($notification);
    }
);

test(
    'notify with invalid message',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn([]);
        Log::shouldReceive()->never();
        assertTrue(true);
        $phone->notify($notification);
    }
);

it(
    'can not notify notification missing toSms method',
    function (): void {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);
        $notification->shouldReceive('via')->andReturn(['sms']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Notification is missing toSms method.');
        $phone->notify($notification);
    }
);

it(
    'can send with log',
    function ($number, $message): void {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        prepareLoggerExpectation()->with(sendString($number, $expectedMessage));
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }
)->with('numbers');
it(
    'can send with template',
    function (): void {
        $number = '18888888888';
        $message = ['template' => 'aaa', 'data' => [111]];
        prepareLoggerExpectation()->with(sendString($number, $message));
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }
);
it(
    'can send with facade',
    function ($number, $message): void {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        prepareLoggerExpectation()->with(sendString($number, $expectedMessage));
        Sms::connection('log')->send($number, $message);
    }
)->with('numbers');

it(
    'can send with alias',
    function ($number, $message): void {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        prepareLoggerExpectation()->with(sendString($number, $expectedMessage));
        \Sms::connection('log')->send($number, $message);
    }
)->with('numbers');

test(
    'create connection missing driver',
    function (): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');
        config()->set('sms.connections.test', []);
        Sms::connection('test');
    }
);

it(
    'can dispatch sending event',
    function ($number, $message): void {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSending::class,
            function (SmsSending $smsSending) use ($number, $expectedMessage) {
                assertSame((string) $number, (string) $smsSending->number);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }
)->with('numbers');
test(
    'create connection with wrong driver',
    function (): void {
        $driver = 'driver';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported driver [{$driver}].");
        config()->set('sms.connections.test', ['driver' => $driver]);
        Sms::connection('test');
    }
);
it(
    'can dispatch sent event',
    function ($number, $message): void {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSent::class,
            function (SmsSent $smsSending) use ($number, $expectedMessage) {
                assertSame((string) $number, (string) $smsSending->number);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }
)->with('numbers');
