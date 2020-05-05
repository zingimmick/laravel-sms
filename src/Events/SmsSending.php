<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;

class SmsSending
{
    /**
     * @var \Overtrue\EasySms\PhoneNumber
     */
    public $number;

    /**
     * @var \Overtrue\EasySms\Message
     */
    public $message;

    /**
     * SmsSending constructor.
     *
     * @param \Overtrue\EasySms\PhoneNumber $number
     * @param \Overtrue\EasySms\Message $message
     */
    public function __construct(PhoneNumber $number, Message $message)
    {
        $this->number = $number;
        $this->message = $message;
    }
}
