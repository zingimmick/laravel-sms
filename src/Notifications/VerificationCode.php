<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Notifications;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;

class VerificationCode extends Notification
{
    public function __construct(
        protected string $code,
        protected int $ttl
    ) {
    }

    /**
     * @return array<class-string<\Zing\LaravelSms\Channels\SmsChannel>>
     */
    public function via(): array
    {
        return [SmsChannel::class];
    }

    public function toSms(): string
    {
        return sprintf(config('sms.verification.content'), $this->code, $this->ttl);
    }
}
