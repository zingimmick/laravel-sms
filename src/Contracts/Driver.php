<?php

namespace Zing\LaravelSms\Contracts;

use Zing\LaravelSms\Exceptions\CouldNotSendNotification;

interface Driver
{
    /**
     * @param \Zing\LaravelSms\Contracts\PhoneNumber $number
     * @param \Zing\LaravelSms\Contracts\Message $message
     *
     * @return mixed
     *
     * @throws CouldNotSendNotification
     */
    public function sendFormatted(PhoneNumber $number, Message $message);
}