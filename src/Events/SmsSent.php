<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;

class SmsSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
