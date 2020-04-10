<?php

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Zing\LaravelSms\Drivers\YunPianDriver;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\SmsManager;

class SmsManagerTest extends TestCase
{
    public function provideNumberAndMessage()
    {
        return [
            [18817393279, Message::text('验证码 123456，您正在进行如糖身份验证，打死也不要告诉别人哦!11111')],
        ];
    }

    /**
     * @dataProvider provideNumberAndMessage
     */
    public function test_get_default_driver($number, $message)
    {
        $sms = Mockery::mock(SmsManager::class);
        $yunpianDriver = Mockery::mock(YunPianDriver::class);
        $sms->shouldReceive('connection')->with('yunpian')->andReturn($yunpianDriver);
        $yunpianDriver->shouldReceive('send')->with($number, $message)->andReturn(true);
        self::assertInstanceOf(YunPianDriver::class, $sms->connection('yunpian'));
        $sms->connection('yunpian')->send($number, $message);
    }

    /**
     * @dataProvider provideNumberAndMessage
     */
    public function test_log($number, $message)
    {
        Log::shouldReceive('info')->once()->with("number: {$number}, content: {$message->getContent()}.");
        $sms = app(SmsManager::class);
        $sms->connection('log')->send($number, $message);
    }

    public function test_notify()
    {
        $phone = new Phone('18817393279');
        $notification = new VerifyCode();
        Log::shouldReceive('info')->once()->with("number: {$phone->routeNotificationForSms()}, content: {$notification->toSms($phone)->getContent()}.");
        $phone->notify($notification);
    }

    public function test_route_notify()
    {
        $phone = new Phone('18817393279');
        $notification = new VerifyCode();
        Log::shouldReceive('info')->once()->with("number: {$phone->routeNotificationForSms()}, content: {$notification->toSms($phone)->getContent()}.");
        Notification::route('sms','18817393279')->notify($notification);
    }
}
