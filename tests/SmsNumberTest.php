<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Mockery;
use Overtrue\EasySms\Message;
use Zing\LaravelSms\SmsNumber;

it("notify",function (){
    $smsNumber = new SmsNumber('18188888888');
    $verifyCode = new VerifyCode();
    prepareLoggerExpectation()->with(sendString($smsNumber->routeNotificationForSms(), $verifyCode->toSms($smsNumber)));
    $smsNumber->notify($verifyCode);

});

