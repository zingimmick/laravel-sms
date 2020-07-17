<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Connectors;

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Throwable;
use Zing\LaravelSms\Events\SmsSending;
use Zing\LaravelSms\Events\SmsSent;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;
use function is_array;
use function trim;

class Connector implements ConnectorInterface
{
    /**
     * @var \Overtrue\EasySms\Contracts\GatewayInterface
     */
    protected $driver;

    /**
     * @var \Overtrue\EasySms\Support\Config
     */
    protected $config;

    /**
     * Connector constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = new Config($config);
    }

    /**
     * @param array $config
     *
     * @throws \Zing\LaravelSms\Exceptions\InvalidArgumentException
     *
     * @return $this|object
     */
    public function connect(array $config)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        $driverClass = $config['driver'];
        if (!class_exists($driverClass) || !in_array(GatewayInterface::class, class_implements($driverClass), true)) {
            throw new InvalidArgumentException("Unsupported driver [{$driverClass}].");
        }

        $this->driver = new $driverClass($config);

        return $this;
    }

    /**
     * @param string|\Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     *
     * @return \Overtrue\EasySms\Contracts\PhoneNumberInterface
     */
    protected function formatPhoneNumber($number)
    {
        if ($number instanceof PhoneNumberInterface) {
            return $number;
        }

        return new PhoneNumber(trim($number));
    }

    /**
     * @param array|string|\Overtrue\EasySms\Contracts\MessageInterface $message
     *
     * @return \Overtrue\EasySms\Contracts\MessageInterface
     */
    protected function formatMessage($message)
    {
        if (!($message instanceof MessageInterface)) {
            if (!is_array($message)) {
                $message = [
                    'content' => $message,
                    'template' => $message,
                ];
            }

            $message = new Message($message);
        }

        return $message;
    }

    /**
     * @param mixed $number
     * @param mixed $message
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     * @throws \Throwable
     *
     * @return bool|mixed
     */
    public function send($number, $message)
    {
        $number = $this->formatPhoneNumber($number);
        $message = $this->formatMessage($message);

        try {
            Event::dispatch(new SmsSending($number, $message));
            Log::debug(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent($this->driver), $message->getTemplate($this->driver), json_encode($message->getData($this->driver)), $message->getMessageType()));
            $result = $this->driver->send($number, $message, $this->config);
            Log::debug(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent($this->driver), $message->getTemplate($this->driver), json_encode($message->getData($this->driver)), $message->getMessageType()), (array) $result);
            Event::dispatch(new SmsSent($number, $message, $result));

            return $result;
        } catch (Throwable $exception) {
            throw CouldNotSendNotification::captureExceptionInDriver($exception);
        }
    }
}
