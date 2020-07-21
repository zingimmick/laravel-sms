<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class SmsSending
{
    /**
     * @var \Overtrue\EasySms\Contracts\PhoneNumberInterface
     */
    public $number;

    /**
     * @var \Overtrue\EasySms\Contracts\MessageInterface
     */
    public $message;

    /**
     * SmsSending constructor.
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     */
    public function __construct(PhoneNumberInterface $number, MessageInterface $message)
    {
        $this->number = $number;
        $this->message = $message;
    }
}
