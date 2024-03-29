<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Events\SmsSending;
use Zing\LaravelSms\Events\SmsSent;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsManager;
use Zing\LaravelSms\SmsMessage;

/**
 * @internal
 */
final class SmsManagerTest extends TestCase
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
    public static function provideNumberAndMessage(): \Iterator
    {
        yield ['18888888888', 'test'];

        yield [new PhoneNumber('18888888888', '+86'), SmsMessage::text('test')];
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testDefaultDriver(PhoneNumberInterface|string $number, MessageInterface|string $message): void
    {
        $this->prepareLoggerExpectation()
            ->with($this->sendString($number, $message));

        /** @var \Zing\LaravelSms\SmsManager $sms */
        $sms = app(SmsManager::class);
        $sms->send($number, $message);
    }

    /**
     * @param \Overtrue\EasySms\Contracts\MessageInterface|array<string, mixed>|string $message
     */
    private function sendString(
        PhoneNumberInterface|string $number,
        array|MessageInterface|string $message
    ): string {
        $message = $this->formatMessage($message);

        return $this->formatLog($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testLogChannel(PhoneNumberInterface|string $number, MessageInterface|string $message): void
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
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testLogLevel(PhoneNumberInterface|string $number, MessageInterface|string $message): void
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
        $notification = \Mockery::mock(VerifyCode::class . '[via]');
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
        $notification = \Mockery::mock(VerifyCode::class . '[toSms]');
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
        $phone = \Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
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
        $notification = \Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')
            ->once()
            ->with($phone)
            ->andReturn([]);
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function testNotifyNotificationMissingToSmsMethod(): void
    {
        $phone = new Phone('18888888888');
        $notification = \Mockery::mock(\Illuminate\Notifications\Notification::class);
        $notification->shouldReceive('via')
            ->andReturn(['sms']);
        $this->expectException(\RuntimeException::class);
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
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testLog(PhoneNumberInterface|string $number, MessageInterface|string $message): void
    {
        $this->prepareLoggerExpectation()
            ->with($this->formatLog($number, $this->formatMessage($message)));
        $sms = app(SmsManager::class);
        $sms->connection('log')
            ->send($number, $message);
    }

    /**
     * @phpstan-return \Mockery\Expectation
     */
    private function prepareLoggerExpectation(?string $channel = null, string $level = 'info')
    {
        Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = \Mockery::mock());
        Log::shouldReceive('debug')->withAnyArgs()->twice();

        return $logChannel->shouldReceive($level)
            ->once();
    }

    private function formatLog(PhoneNumberInterface|string $number, MessageInterface $message): string
    {
        return sprintf(
            'number: %s, message: "%s", template: "%s", data: %s, type: %s',
            $number,
            $message->getContent(),
            $message->getTemplate(),
            json_encode($message->getData(), JSON_THROW_ON_ERROR),
            $message->getMessageType()
        );
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testFacade(PhoneNumberInterface|string $number, MessageInterface|string $message): void
    {
        $this->prepareLoggerExpectation()
            ->with($this->formatLog($number, $this->formatMessage($message)));
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
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testSmsSending(PhoneNumberInterface|string $number, MessageInterface|string $message): void
    {
        $expectedMessage = $this->formatMessage($message);

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSending::class,
            static function (SmsSending $smsSending) use ($number, $expectedMessage): bool {
                self::assertSame((string) $number, (string) $smsSending->phoneNumber);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }

    /**
     * @param \Overtrue\EasySms\Contracts\MessageInterface|string|array<string, mixed> $message
     */
    private function formatMessage(array|MessageInterface|string $message): MessageInterface
    {
        if (\is_string($message)) {
            return new Message([
                'content' => $message,
                'template' => $message,
            ]);
        }

        if (\is_array($message)) {
            return new Message($message);
        }

        return $message;
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param string|\Overtrue\EasySms\PhoneNumber $number
     * @param string|\Zing\LaravelSms\SmsMessage $message
     */
    public function testSmsSent(PhoneNumberInterface|string $number, MessageInterface|string $message): void
    {
        $expectedMessage = $this->formatMessage($message);

        Event::fake();
        Sms::connection('log')->send($number, $message);
        Event::assertDispatched(
            SmsSent::class,
            static function (SmsSent $smsSending) use ($number, $expectedMessage): bool {
                self::assertSame((string) $number, (string) $smsSending->phoneNumber);
                self::assertSameMessage($expectedMessage, $smsSending->message);

                return true;
            }
        );
    }

    public function testVia(): void
    {
        $manager = \Mockery::mock(SmsManager::class);
        $manager->shouldReceive('via')
            ->passthru();
        $manager->shouldReceive('connection')
            ->withArgs([self::NAME])
            ->once()
            ->andReturn(\Mockery::mock(Connector::class));
        $manager->via(self::NAME);
    }
}
