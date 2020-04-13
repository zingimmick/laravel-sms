<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;

class NullDriver extends LocalDriver
{
    public function sendFormatted(PhoneNumber $number, Message $message)
    {
        // do nothing.
    }
}
