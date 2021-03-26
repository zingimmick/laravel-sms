<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class SmsSent
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
     * @var mixed
     */
    public $result;

    /**
     * SmsSending constructor.
     *
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     * @param mixed $result
     */
    public function __construct(PhoneNumberInterface $number, MessageInterface $message, $result)
    {
        $this->number = $number;
        $this->message = $message;
        $this->result = $result;
    }
}
