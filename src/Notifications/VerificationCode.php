<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Notifications;

use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Channels\SmsChannel;

class VerificationCode extends Notification
{
    /**
     * @var string|int
     */
    protected $code;

    /**
     * @var int
     */
    protected $ttl;

    /**
     * VerificationCode constructor.
     *
     * @param string|int $code
     * @param int $ttl
     */
    public function __construct($code, $ttl)
    {
        $this->code = $code;
        $this->ttl = $ttl;
    }

    public function via(): array
    {
        return [SmsChannel::class];
    }

    public function toSms(): string
    {
        return sprintf(config('sms.verification.content'), $this->code, $this->ttl);
    }
}
