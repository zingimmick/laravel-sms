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
     * @return \Zing\LaravelSms\SmsMessage|array<string, mixed>|string
     */
    public function toSms(mixed $notifiable): array|SmsMessage|string
    {
        if ($notifiable) {
            return SmsMessage::text('')->onConnection('log');
        }

        return '';
    }
}
