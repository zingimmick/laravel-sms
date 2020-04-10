<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Messages\SmsMessage;

class NullDriver extends Driver
{
    public function send($number, SmsMessage $message)
    {
    }
}
