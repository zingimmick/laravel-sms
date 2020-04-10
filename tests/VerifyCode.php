<?php

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Messages\SmsMessage;

class VerifyCode extends Notification
{
    public function via()
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return SmsMessage::text('')->onConnection('log');
    }
}
