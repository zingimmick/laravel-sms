<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;

class NullDriver extends Driver
{
    public function sendMessage(PhoneNumber $number, Message $message)
    {
    }
}
