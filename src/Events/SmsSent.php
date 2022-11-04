<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class SmsSent
{
    public function __construct(
        public PhoneNumberInterface $phoneNumber,
        public MessageInterface $message,
        public mixed $result
    ) {
    }
}
