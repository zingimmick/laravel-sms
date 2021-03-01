<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\SmsNumber;

it(
    'notify',
    function (): void {
        $smsNumber = new SmsNumber('18188888888');
        $verifyCode = new VerifyCode();
        prepareLoggerExpectation()->with(sendString($smsNumber->routeNotificationForSms(), $verifyCode->toSms($smsNumber)));
        $smsNumber->notify($verifyCode);
    }
);
