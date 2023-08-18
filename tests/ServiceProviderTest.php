<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;

/**
 * @internal
 */
final class ServiceProviderTest extends TestCase
{
    public function testSms(): void
    {
        $this->assertInstanceOf(Connector::class, Sms::connection());
    }

    public function testSend(): void
    {
        $phoneNumber = new PhoneNumber(18_188_888_888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($phoneNumber, $message);
    }

    public function testAlias(): void
    {
        $this->assertSame(
            forward_static_call([\Sms::class, 'connection']),
            forward_static_call([Sms::class, 'connection'])
        );
    }
}
