<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\SmsMessage;

class VerifyCode extends Notification
{
    public function via(): array
    {
        return [SmsChannel::class];
    }

    /**
     * @param mixed $notifiable
     * @return string|\Zing\LaravelSms\SmsMessage
     */
    public function toSms($notifiable)
    {
        if ($notifiable) {
            return SmsMessage::text('')->onConnection('log');
        }

        return '';
    }
}
