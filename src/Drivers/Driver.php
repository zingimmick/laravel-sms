<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Messages\SmsMessage;
use Zing\LaravelSms\Support\HasHttpRequest;

abstract class Driver
{
    use HasHttpRequest;

    public $client;

    public $config;

    public function __construct($config = null)
    {
        $this->config = collect($config);
        $this->client = $this->getHttpClient($this->getBaseOptions());
    }

    /**
     * @param $number
     * @param \Zing\LaravelSms\Messages\SmsMessage $message
     *
     * @return bool|mixed
     *
     * @throws \Zing\LaravelSms\Exceptions\MessageSendErrorException
     */
    abstract public function send($number, SmsMessage $message);
}
