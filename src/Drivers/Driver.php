<?php

namespace Zing\LaravelSms\Drivers;

use Illuminate\Support\Facades\Log;
use Zing\LaravelSms\Contracts\Driver as DriverContract;
use Zing\LaravelSms\Contracts\Message as MessageContract;
use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Support\HasHttpRequest;

abstract class Driver implements DriverContract
{

    public $config;


    /**
     * @param PhoneNumberContract|string $number
     *
     * @return PhoneNumberContract
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
            return Message::fromTemplate($message['template'] ?? '', $message['data'] ?? []);
        }

        return Message::text($message);
    }

    /**
     * @param mixed $number
     * @param mixed $message
     *
     * @return bool|mixed
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     * @throws \Throwable
     */
    public function send($number, $message)
    {
        try {
            $number = $this->formatPhoneNumber($number);
            $message = $this->formatMessage($message);

            $this->sending($number, $message);
            Log::debug("number: {$number}, content: {$message}.");
            $result = $this->sendFormatted($number, $message);
            Log::debug("number: {$number}, content: {$message}.", (array) $result);
            $this->sent($number, $message, $result);

            return $result;
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::captureExceptionInDriver($exception);
        }
    }

    public function sending($number, $message)
    {
    }

    public function sent($number, $message, $result)
    {
    }
}
