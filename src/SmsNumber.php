<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Notifications\Notifiable;
use Overtrue\EasySms\PhoneNumber;

class SmsNumber extends PhoneNumber
{
    use Notifiable;

    public function routeNotificationForSms(\Illuminate\Notifications\Notification $notification): self
    {
        return $this;
    }
}
