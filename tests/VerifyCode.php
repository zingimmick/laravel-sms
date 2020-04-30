<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Message;

class VerifyCode extends Notification
{
    public function via()
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return Message::text('')->onConnection('log');
    }
}
