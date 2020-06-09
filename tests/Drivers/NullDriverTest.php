<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;

it(
    'can send message',
    function (): void {
        $number = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        assertTrue(true);
        Sms::connection('null')->send($number, $message, new Config());
    }
);
it(
    'can send message with alias',
    function (): void {
        $number = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        assertTrue(true);
        \Sms::connection('null')->send($number, $message, new Config());
    }
);
