<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Concerns;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;

trait ServiceProviderTests
{
    public function testSms(): void
    {
        $this->assertInstanceOf(Connector::class, Sms::connection());
    }

    public function testSend(): void
    {
        $number = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($number, $message, new Config());
    }

    public function testAlias(): void
    {
        $this->assertSame(forward_static_call([\Sms::class,'connection']),forward_static_call([Sms::class,'connection']));
    }
}
