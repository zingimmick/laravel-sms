<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Contracts\Driver as DriverContract;
use Zing\LaravelSms\Contracts\Message as MessageContract;
use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;
use Zing\LaravelSms\Exceptions\CannotSendNotification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Support\HasHttpRequest;

abstract class Driver implements DriverContract
{
    use HasHttpRequest;

    public $client;

    public $config;

    public function __construct($config = null)
    {
        $this->config = new Config($config);
        $this->client = $this->getHttpClient($this->getBaseOptions());
    }

    /**
     * @param string|\Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     *
     * @return \Overtrue\EasySms\Contracts\PhoneNumberInterface|string|\Zing\LaravelSms\Contracts\PhoneNumber
     */
    protected function formatPhoneNumber($number)
    {
        if ($number instanceof PhoneNumberContract) {
            return $number;
        }

        return new PhoneNumber(trim($number));
    }

    /**
     * @param array|string|MessageContract $message
     *
     * @return array|string|\Zing\LaravelSms\Contracts\Message
     */
    protected function formatMessage($message): MessageContract
    {
        if ($message instanceof MessageContract) {
            return $message;
        }
        if (is_array($message)) {
            return Message::template($message['template'] ?? '', $message['data'] ?? []);
        }

        return Message::text($message);
    }

    /**
     * @param mixed $number
     * @param mixed $message
     *
     * @return bool|mixed
     *
     * @throws \Zing\LaravelSms\Exceptions\CannotSendNotification
     */
    public function send($number, $message)
    {
        $number = $this->formatPhoneNumber($number);
        $message = $this->formatMessage($message);

        return $this->sendMessage($number, $message);
    }

    /**
     * @param \Zing\LaravelSms\Contracts\PhoneNumber $number
     * @param \Zing\LaravelSms\Contracts\Message $message
     *
     * @return mixed
     *
     * @throws CannotSendNotification
     */
    abstract protected function sendMessage(PhoneNumberContract $number, MessageContract $message);
}
