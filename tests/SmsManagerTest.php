<?php

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use RuntimeException;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Contracts\Message as MessageContract;
use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;
use Zing\LaravelSms\Drivers\YunpianDriver;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\SmsManager;

class SmsManagerTest extends TestCase
{
    public function provideNumberAndMessage()
    {
        return [
            ['18888888888', 'test'],
            [new PhoneNumber('18888888888', '+86'), Message::text('test')],
        ];
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_default_driver($number, $message)
    {
        /** @var SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation()->with("number: {$number}, content: {$message}.");
        $sms->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_log_channel($number, $message)
    {
        $channel = 'test';
        config()->set('sms.connections.log.channel', $channel);
        /** @var SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation($channel)->with("number: {$number}, content: {$message}.");
        $sms->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_log_level($number, $message)
    {
        $level = 'info';
        config()->set('sms.connections.log.level', $level);
        /** @var SmsManager $sms */
        $sms = app(SmsManager::class);
        $this->prepareLoggerExpectation(null, $level)->with("number: {$number}, content: {$message}.");
        $sms->send($number, $message);
    }

    public function test_notify()
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with("number: {$phone->routeNotificationForSms($notification)}, content: {$notification->toSms($phone)}.");
        $phone->notify($notification);
    }

    public function test_notify_alias()
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[via]');
        $notification->shouldReceive('via')->andReturn(['sms']);
        $this->prepareLoggerExpectation()->with("number: {$phone->routeNotificationForSms($notification)}, content: {$notification->toSms($phone)}.");
        $phone->notify($notification);
    }

    public function test_route_notify()
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with("number: {$phone->routeNotificationForSms($notification)}, content: {$notification->toSms($phone)}.");
        Notification::route(SmsChannel::class, '18888888888')->notify($notification);
    }

    public function test_route_notify_alias()
    {
        $phone = new Phone('18888888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with("number: {$phone->routeNotificationForSms($notification)}, content: {$notification->toSms($phone)}.");
        Notification::route('sms', '18888888888')->notify($notification);
    }

    public function test_notify_string()
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn('test');
        $this->prepareLoggerExpectation()->with("number: {$phone->routeNotificationForSms($notification)}, content: {$notification->toSms($phone)}.");
        $phone->notify($notification);
    }

    public function test_notify_invalid_receiver()
    {
        /** @var \Zing\LaravelSms\Tests\Phone $phone */
        $phone = Mockery::mock(Phone::class . '[routeNotificationForSms]', ['18888888888']);
        $notification = new VerifyCode();
        $phone->shouldReceive('routeNotificationForSms')->once()->andReturn('');
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function test_notify_invalid_message()
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(VerifyCode::class . '[toSms]');
        $notification->shouldReceive('toSms')->with($phone)->andReturn([]);
        Log::shouldReceive()->never();
        $phone->notify($notification);
    }

    public function test_notify_notification_missing_to_sms_method()
    {
        $phone = new Phone('18888888888');
        $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);
        $notification->shouldReceive('via')->andReturn(['sms']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Notification is missing toSms method.');
        $phone->notify($notification);
    }

    public function test_template()
    {
        $number = '18888888888';
        $message = ['template' => 'aaa', 'data' => [111]];
        $expectedMessage = Message::fromTemplate($message['template'], $message['data']);
        $this->prepareLoggerExpectation()->with("number: {$number}, content: {$expectedMessage}.");
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_get_yunpian($number, $message)
    {
        $sms = Mockery::mock(SmsManager::class);
        $yunpianDriver = Mockery::mock(YunpianDriver::class);
        $sms->shouldReceive('connection')->with('yunpian')->andReturn($yunpianDriver);
        $yunpianDriver->shouldReceive('send')->with($number, $message)->andReturn(true);
        self::assertInstanceOf(YunpianDriver::class, $sms->connection('yunpian'));
        $sms->connection('yunpian')->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_log($number, $message)
    {
        $this->prepareLoggerExpectation()->with("number: {$number}, content: {$message}.");
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
     * @param PhoneNumberContract|string $number
     * @param MessageContract|string $message
     */
    public function test_facade($number, $message)
    {
        $this->prepareLoggerExpectation()->with("number: {$number}, content: {$message}.");
        Sms::connection('log')->send($number, $message);
    }

    public function test_connection_without_driver()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');
        config()->set('sms.connections.test', []);
        Sms::connection('test');
    }

    public function test_connection_with_wrong_driver()
    {
        $driver = 'driver';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported driver [${driver}].");
        config()->set('sms.connections.test', ['driver' => $driver]);
        Sms::connection('test');
    }
}
