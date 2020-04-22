<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\Tests\TestCase;

class NullDriverTest extends TestCase
{
    public function test_send()
    {
        $number = new PhoneNumber(18188888888);
        $message = Message::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($number, $message, new Config());
    }

    public function test_send1()
    {
        $number = new PhoneNumber(18188888888);
        $message = Message::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        \Sms::connection('null')->send($number, $message, new Config());
    }
}
