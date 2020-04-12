<?php

namespace Zing\LaravelSms\Drivers;

use Illuminate\Support\Facades\Log;
use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;

class LogDriver extends Driver
{
    public function sendFormatted(PhoneNumber $number, Message $message)
    {
        $channel = $this->config->get('channel');
        $level = $this->config->get('level', 'info');
        Log::channel($channel)->{$level}("number: {$number}, content: {$message}.");
    }
}
