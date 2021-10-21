<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\SmsMessage;

class VerifyCode extends Notification
{
    /**
     * @return array<class-string<\Zing\LaravelSms\Channels\SmsChannel>>
     */
    public function via(): array
    {
        return [SmsChannel::class];
    }

    /**
     * @param mixed $notifiable
     *
     * @return \Zing\LaravelSms\SmsMessage|string
     */
    public function toSms($notifiable)
    {
        if ($notifiable) {
            return SmsMessage::text('')->onConnection('log');
        }

        return '';
    }
}
