<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Concerns;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use function PHPUnit\Framework\assertInstanceOf;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;

trait ServiceProviderTests
{
    public function testSms(): void
    {
        assertInstanceOf(Connector::class, Sms::connection());
    }

    public function testSend(): void
    {
        $number = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($number, $message, new Config());
    }

    public function testSendWithAlias(): void
    {
        $number = new PhoneNumber(18188888888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        \Sms::connection('null')->send($number, $message, new Config());
    }
}
