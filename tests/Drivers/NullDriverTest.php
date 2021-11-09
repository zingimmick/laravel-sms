<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\Tests\TestCase;

class NullDriverTest extends TestCase
{
    public function testSend(): void
    {
        $phoneNumber = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($phoneNumber, $message);
    }
}
