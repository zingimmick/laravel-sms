<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Zing\LaravelSms\Channels\SmsChannel;

class VerificationCode extends Notification
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $ttl;

    /**
     * VerificationCode constructor.
     *
     * @param string $code
     * @param int $ttl
     */
    public function __construct($code, $ttl)
    {
        $this->code = $code;
        $this->ttl = $ttl;
    }

    public function via()
    {
        return [SmsChannel::class];
    }

    public function toSms()
    {
        return sprintf(config('sms.verification.content'), $this->code, $this->ttl);
    }
}
