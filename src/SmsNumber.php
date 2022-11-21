<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Overtrue\EasySms\PhoneNumber;

class SmsNumber extends PhoneNumber
{
    use Notifiable;

    public function routeNotificationForSms(Notification $notification): self
    {
        return $this;
    }
}
