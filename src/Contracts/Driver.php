<?php

namespace Zing\LaravelSms\Contracts;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

interface Driver
{
    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     *
     * @return mixed
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     */
    public function send(PhoneNumberInterface $number, MessageInterface $message);
}
