<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;
use RuntimeException;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Events\SmsSending;
use Zing\LaravelSms\Events\SmsSent;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsManager;
use Zing\LaravelSms\SmsMessage;

class SmsManagerTest extends TestCase
{
    /**
     * @var string
     */
    private const CHANNEL = 'test';

    /**
     * @var string
     */
    private const LEVEL = 'info';

    /**
     * @var string
     */
    private const DRIVER = 'driver';

    /**
     * @var string
     */
    private const NAME = 'test';

    /**
     * @var string
     */
    private const NUMBER = '18888888888';

    /**
     * @var array<string, string|int[]>
     */
    private const MESSAGE = [
        'template' => 'aaa',
        'data' => [111],
    ];

    /**
     * @return \Iterator<array{string|\Overtrue\EasySms\Contracts\PhoneNumberInterface, string|\Overtrue\EasySms\Contracts\MessageInterface}>
     */
    public function provideNumberAndMessage(): \Iterator
    {
        yield ['18888888888', 'test'];
        yield [new PhoneNumber('18888888888', '+86'), SmsMessage::text('test')];
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testDefaultDriver($number, $message): void
    {
        $this->prepareLoggerExpectation()
            ->with($this->sendString($number, $message));
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $sms->send($number, $message);
    }

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|array<string, mixed>|string $message
     */
    protected function sendString($number, $message): string
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

        return sprintf(
            'number: %s, message: "%s", template: "%s", data: %s, type: %s',
            $number,
            $message->getContent(),
            $message->getTemplate(),
            json_encode($message->getData()),
            $message->getMessageType()
        );
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testLogChannel($number, $message): void
    {
        config()
            ->set('sms.connections.log.channel', self::CHANNEL);
        $this->prepareLoggerExpectation(self::CHANNEL)
            ->with($this->sendString($number, $message));
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
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
        config()
            ->set('sms.connections.log.level', self::LEVEL);
        $this->prepareLoggerExpectation(null, self::LEVEL)
            ->with($this->sendString($number, $message));
        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $sms->send($number, $message);
    }

    public function testNotify(): void
    {
        $phone = new Phone('18888888888');
        $verifyCode = new VerifyCode();
        $this->prepareLoggerExpectation()
            ->with($this->sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
        $phone->notify($verifyCode);
    }

    public function testNotifyAlias(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[via]');
        $notification->shouldReceive('via')
            ->andReturn(['sms']);
        $this->prepareLoggerExpectation()
            ->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    public function testRouteNotify(): void
    {
        $verifyCode = new VerifyCode();
        $phone = new Phone('18888888888');
        $this->prepareLoggerExpectation()
            ->with($this->sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
        Notification::route(SmsChannel::class, '18888888888')->notify($verifyCode);
    }

    public function testRouteNotifyAlias(): void
    {
        $verifyCode = new VerifyCode();
        $phone = new Phone('18888888888');
        $this->prepareLoggerExpectation()
            ->with($this->sendString($phone->routeNotificationForSms($verifyCode), $verifyCode->toSms($phone)));
        Notification::route('sms', '18888888888')->notify($verifyCode);
    }

    public function testNotifyString(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')
            ->with($phone)
            ->andReturn('test');
        $this->prepareLoggerExpectation()
            ->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    public function testNotifyInvalidReceiver(): void
    {
        /** @var \Zing\LaravelSms\Tests\Phone|\Mockery\MockInterface $phone */
        $phone = Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
        $phone->shouldReceive('routeNotificationForSms')
            ->once()
            ->andReturn('');
        Log::shouldReceive()->never();
        $verifyCode = new VerifyCode();
        $phone->notify($verifyCode);
    }

    public function testNotifyInvalidMessage(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')
            ->with($phone)
            ->andReturn([]);
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function testNotifyNotificationMissingToSmsMethod(): void
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);
        $notification->shouldReceive('via')
            ->andReturn(['sms']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Notification is missing toSms method.');
        $phone->notify($notification);
    }

    public function testTemplate(): void
    {
        $this->prepareLoggerExpectation()
            ->with($this->sendString(self::NUMBER, self::MESSAGE));
        $sms = app(SmsManager::class);
        $sms->connection('log')
            ->send(self::NUMBER, self::MESSAGE);
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

        $this->prepareLoggerExpectation()
            ->with(
                sprintf(
                    'number: %s, message: "%s", template: "%s", data: %s, type: %s',
                    $number,
                    $expectedMessage->getContent(),
                    $expectedMessage->getTemplate(),
                    json_encode($expectedMessage->getData()),
                    $expectedMessage->getMessageType()
                )
            );
        $sms = app(SmsManager::class);
        $sms->connection('log')
            ->send($number, $message);
    }

    /**
     * @phpstan-return \Mockery\Expectation
     */
    protected function prepareLoggerExpectation(?string $channel = null, string $level = 'info')
    {
        Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = Mockery::mock());
        Log::shouldReceive('debug')->withAnyArgs()->twice();

        return $logChannel->shouldReceive($level)
            ->once();
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

        $this->prepareLoggerExpectation()
            ->with(
                sprintf(
                    'number: %s, message: "%s", template: "%s", data: %s, type: %s',
                    $number,
                    $expectedMessage->getContent(),
                    $expectedMessage->getTemplate(),
                    json_encode($expectedMessage->getData()),
                    $expectedMessage->getMessageType()
                )
            );
        Sms::connection('log')->send($number, $message);
    }

    public function testConnectionWithoutDriver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');
        config()
            ->set('sms.connections.test', []);
        Sms::connection('test');
    }

    public function testConnectionWithWrongDriver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Unsupported driver [%s].', self::DRIVER));
        config()
            ->set('sms.connections.test', [
                'driver' => self::DRIVER,
            ]);
        Sms::connection('test');
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testSmsSending($number, $message): void
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

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSending::class,
            function (SmsSending $smsSending) use ($number, $expectedMessage): bool {
                self::assertSame((string) $number, (string) $smsSending->number);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string $message
     */
    public function testSmsSent($number, $message): void
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

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSent::class,
            function (SmsSent $smsSending) use ($number, $expectedMessage): bool {
                self::assertSame((string) $number, (string) $smsSending->number);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }

    public function testVia(): void
    {
        $manager = Mockery::mock(SmsManager::class);
        $manager->shouldReceive('via')
            ->passthru();
        $manager->shouldReceive('connection')
            ->withArgs([self::NAME])
            ->once()
            ->andReturn(Mockery::mock(Connector::class));
        $manager->via(self::NAME);
    }
}
