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
use function PHPUnit\Framework\assertSame;

it("default driver", function ($number, $message): void {
    prepareLoggerExpectation()->with(sendString($number, $message));
    /** @var \Zing\LaravelSms\SmsManager $sms */
    $sms = app(SmsManager::class);
    $sms->send($number, $message);
})->with('numbers');
it("log channel", function ($number, $message): void {
    $channel = 'test';
    config()->set('sms.connections.log.channel', $channel);
    prepareLoggerExpectation($channel)->with(sendString($number, $message));
    /** @var \Zing\LaravelSms\SmsManager $sms */
    $sms = app(SmsManager::class);
    $sms->send($number, $message);
})->with('numbers');
it("log level", function ($number, $message): void {
    $level = 'info';
    config()->set('sms.connections.log.level', $level);
    prepareLoggerExpectation(null, $level)->with(sendString($number, $message));
    /** @var \Zing\LaravelSms\SmsManager $sms */
    $sms = app(SmsManager::class);
    $sms->send($number, $message);
})->with('numbers');
it("notify", function () {
    $phone = new Phone('18888888888');
    $verifyCode = new VerifyCode();
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
    $phone->notify($verifyCode);
});
it("notify alias", function () {
    $phone = new Phone('18888888888');
    $notification = Mockery::mock(VerifyCode::class . '[via]');
    $notification->shouldReceive('via')->andReturn(['sms']);
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
    $phone->notify($notification);
});
it("route notify", function () {
    $verifyCode = new VerifyCode();
    $phone = new Phone('18888888888');
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
    Notification::route(SmsChannel::class, '18888888888')->notify($verifyCode);
});
it("route notify alias", function () {
    $verifyCode = new VerifyCode();
    $phone = new Phone('18888888888');
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
    Notification::route('sms', '18888888888')->notify($verifyCode);
});
it("notify string", function () {
    $phone = new Phone('18888888888');
    $notification = Mockery::mock(VerifyCode::class . '[toSms]');
    $notification->shouldReceive('toSms')->with($phone)->andReturn('This is a test message.');
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
    $phone->notify($notification);

});
it("notify invalid receiver", function () {
    /** @var \Zing\LaravelSms\Tests\Phone $phone */
    $phone = Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
    $phone->shouldReceive('routeNotificationForSms')->once()->andReturn('');
    Log::shouldReceive()->never();
    $verifyCode = new VerifyCode();
    $phone->notify($verifyCode);

});

it("notify invalid message", function () {
    $phone = new Phone('18888888888');
    $notification = Mockery::mock(VerifyCode::class . '[toSms]');
    $notification->shouldReceive('toSms')->with($phone)->andReturn([]);
    Log::shouldReceive()->never();
    $phone->notify($notification);
});

it("notify notification missing to sms method", function () {
    $phone = new Phone('18888888888');
    $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);
    $notification->shouldReceive('via')->andReturn(['sms']);
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Notification is missing toSms method.');
    $phone->notify($notification);

});

it("template", function () {
    $number = '18888888888';
    $message = [
        'template' => 'aaa',
        'data' => [111],
    ];
    prepareLoggerExpectation()->with(sendString($number, $message));
    $sms = app(SmsManager::class);
    $sms->connection('log')->send($number, $message);

});
it("log", function ($number, $message): void {
    $expectedMessage = $message;
    if (is_string($expectedMessage)) {
        $expectedMessage = new Message(
            [
                'content' => $expectedMessage,
                'template' => $expectedMessage,
            ]
        );
    }

    prepareLoggerExpectation()->with(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $expectedMessage->getContent(), $expectedMessage->getTemplate(), json_encode($expectedMessage->getData()), $expectedMessage->getMessageType()));
    $sms = app(SmsManager::class);
    $sms->connection('log')->send($number, $message);
})->with('numbers');
it("facade", function ($number, $message): void {
    $expectedMessage = $message;
    if (is_string($expectedMessage)) {
        $expectedMessage = new Message(
            [
                'content' => $expectedMessage,
                'template' => $expectedMessage,
            ]
        );
    }

    prepareLoggerExpectation()->with(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $expectedMessage->getContent(), $expectedMessage->getTemplate(), json_encode($expectedMessage->getData()), $expectedMessage->getMessageType()));
    Sms::connection('log')->send($number, $message);
})->with('numbers');
it("connection without driver", function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('A driver must be specified.');
    config()->set('sms.connections.test', []);
    Sms::connection('test');
});
it("connection with wrong driver", function () {
    $driver = 'driver';
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage("Unsupported driver [{$driver}].");
    config()->set(
        'sms.connections.test',
        [
            'driver' => $driver,
        ]
    );
    Sms::connection('test');
});
it("sms sending", function ($number, $message): void {
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
            assertSameMessage($expectedMessage, $smsSending->message);

            return true;
        }
    );
})->with('numbers');
it("sms sent", function ($number, $message): void {
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
            assertSameMessage($expectedMessage, $smsSending->message);

            return true;
        }
    );
})->with('numbers');
it('via', function () {
    $name = 'test';
    $manager = Mockery::mock(SmsManager::class);
    $manager->shouldReceive('via')->passthru();
    $manager->shouldReceive('connection')->withArgs([$name])->once();
    $manager->via($name);
});
