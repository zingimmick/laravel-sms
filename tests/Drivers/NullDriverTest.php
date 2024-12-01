<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\Tests\TestCase;use PHPUnit\Framework\Attributes\WithoutErrorHandler;

/**
 * @internal
 */
final class NullDriverTest extends TestCase
{
    #[WithoutErrorHandler]
    public function testSend(): void
    {
        $phoneNumber = new PhoneNumber(18_188_888_888);
        $message = SmsMessage::text('【test】This is a test message.');
        Log::shouldReceive('channel')->withAnyArgs()->twice();
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        Sms::connection('null')->send($phoneNumber, $message);
    }
}
