<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;
use RuntimeException;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsManager;

class SmsManagerTest extends TestCase
{
    public function provideNumberAndMessage()
    {
        return [
            ['18888888888', 'test'],
            [new PhoneNumber('18888888888', '+86'), \Zing\LaravelSms\SmsMessage::text('test')],
        ];
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testDefaultDriver($number, $message): void
    {
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation()->with($this->sendString($number, $message));
        $sms->send($number, $message);
    }

    protected function sendString($number, $message)
    {
        if (is_string($message)) {
            $message = new Message(
                [
                    'content' => $message,
                    'template' => $message,
                ]
            );
        }

        if (is_array($message)) {
            $message = new Message($message);
        }

        return sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent(), $message->getTemplate(), json_encode($message->getData()), $message->getMessageType());
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testLogChannel($number, $message): void
    {
        $channel = 'test';
        config()->set('sms.connections.log.channel', $channel);
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation($channel)->with($this->sendString($number, $message));
        $sms->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testLogLevel($number, $message): void
    {
        $level = 'info';
        config()->set('sms.connections.log.level', $level);
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation(null, $level)->with($this->sendString($number, $message));
        $sms->send($number, $message);
    }

    public function testNotify(): void
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    public function testNotifyAlias(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[via]');
        $notification->shouldReceive('via')->andReturn(['sms']);
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    public function testRouteNotify(): void
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        Notification::route(SmsChannel::class, '18888888888')->notify($notification);
    }

    public function testRouteNotifyAlias(): void
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        Notification::route('sms', '18888888888')->notify($notification);
    }

    public function testNotifyString(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn('test');
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    public function testNotifyInvalidReceiver(): void
    {
        /** @var \Zing\LaravelSms\Tests\Phone $phone */
        $phone = Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
        $notification = new VerifyCode();
        $phone->shouldReceive('routeNotificationForSms')->once()->andReturn('');
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function testNotifyInvalidMessage(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn([]);
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function testNotifyNotificationMissingToSmsMethod(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);
        $notification->shouldReceive('via')->andReturn(['sms']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Notification is missing toSms method.');
        $phone->notify($notification);
    }

    public function testTemplate(): void
    {
        $number = '18888888888';
        $message = ['template' => 'aaa', 'data' => [111]];
        $this->prepareLoggerExpectation()->with($this->sendString($number, $message));
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testLog($number, $message): void
    {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        $this->prepareLoggerExpectation()->with(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $expectedMessage->getContent(), $expectedMessage->getTemplate(), json_encode($expectedMessage->getData()), $expectedMessage->getMessageType()));
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }

    protected function prepareLoggerExpectation($channel = null, $level = 'info')
    {
        Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = Mockery::mock());
        Log::shouldReceive('debug')->withAnyArgs()->twice();

        return $logChannel->shouldReceive($level)->once();
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testFacade($number, $message): void
    {
        $expectedMessage = $message;
        if (is_string($expectedMessage)) {
            $expectedMessage = new Message(
                [
                    'content' => $expectedMessage,
                    'template' => $expectedMessage,
                ]
            );
        }

        $this->prepareLoggerExpectation()->with(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $expectedMessage->getContent(), $expectedMessage->getTemplate(), json_encode($expectedMessage->getData()), $expectedMessage->getMessageType()));
        Sms::connection('log')->send($number, $message);
    }

    public function testConnectionWithoutDriver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');
        config()->set('sms.connections.test', []);
        Sms::connection('test');
    }

    public function testConnectionWithWrongDriver(): void
    {
        $driver = 'driver';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported driver [{$driver}].");
        config()->set('sms.connections.test', ['driver' => $driver]);
        Sms::connection('test');
    }
}
