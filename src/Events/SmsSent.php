<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;

class SmsSent
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
     * @var mixed
     */
    public $result;

    /**
     * SmsSending constructor.
     *
     * @param \Overtrue\EasySms\PhoneNumber $number
     * @param \Overtrue\EasySms\Message $message
     * @param mixed $result
     */
    public function __construct(PhoneNumber $number, Message $message, $result)
    {
        $this->number = $number;
        $this->message = $message;
        $this->result = $result;
    }
}
