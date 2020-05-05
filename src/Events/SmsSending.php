<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;

class SmsSending
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
