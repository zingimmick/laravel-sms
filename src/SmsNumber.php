<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Notifications\Notifiable;

class SmsNumber extends \Overtrue\EasySms\PhoneNumber
{
    use Notifiable;

    public function routeNotificationForSms()
    {
        return $this;
    }
}
