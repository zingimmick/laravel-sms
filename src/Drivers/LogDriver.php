<?php

namespace Zing\LaravelSms\Drivers;

use Illuminate\Support\Facades\Log;
use Zing\LaravelSms\Messages\SmsMessage;

class LogDriver extends Driver
{
    public function send($number, SmsMessage $message)
    {
        Log::info("number: {$number}, content: {$message->getContent()}.");
    }
}
