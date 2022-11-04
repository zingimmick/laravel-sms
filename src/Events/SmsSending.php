<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class SmsSending
{
    public function __construct(
        public PhoneNumberInterface $phoneNumber,
        public MessageInterface $message
    ) {
    }
}
