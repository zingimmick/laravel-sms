<?php

namespace Zing\LaravelSms\Drivers;

use Illuminate\Support\Facades\Log;
use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;

class LogDriver extends Driver
{
    public function sendMessage(PhoneNumber $number, Message $message)
    {
        Log::info("number: {$number}, content: {$message->getContent()}.");
    }
}
