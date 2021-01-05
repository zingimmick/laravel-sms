<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\PhoneNumber;
use Zing\LaravelSms\Connectors\Connector;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsMessage;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

it("sms", function () {
    assertInstanceOf(Connector::class, Sms::connection());
});
it("send", function () {
    $phoneNumber = new PhoneNumber(18188888888);
    $message = SmsMessage::text('【test】This is a test message.');
    Log::shouldReceive('debug')->withAnyArgs()->twice();
    Sms::connection('null')->send($phoneNumber, $message);

});
it("alias", function () {
    assertSame(forward_static_call([\Sms::class, 'connection']), forward_static_call([Sms::class, 'connection']));
});