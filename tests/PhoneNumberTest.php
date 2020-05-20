<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\PhoneNumber;

it('can notify', function (): void {
    $phone = new PhoneNumber('18188888888');
    $notification = new VerifyCode();
    prepareLoggerExpectation()->with(sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
    $phone->notify($notification);
});
