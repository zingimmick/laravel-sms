<?php

namespace Zing\LaravelSms;

use Illuminate\Notifications\Notifiable;

class PhoneNumber extends \Overtrue\EasySms\PhoneNumber
{
    use Notifiable;

    public function routeNotificationForSms($notification)
    {
        return $this;
    }
}
