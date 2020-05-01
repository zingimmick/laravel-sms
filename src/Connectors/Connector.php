<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Connectors;

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Facades\Log;
use function is_array;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Support\Config;
use function trim;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;

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
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = new Config($config);
    }

    public function connect(array $config)
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        $driver = $config['driver'];
        if (! class_exists($driver) || ! in_array(GatewayInterface::class, class_implements($driver), true)) {
            throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
        }

        $this->driver = new $driver($config);

        return $this;
    }

    /**
     * @param string|\Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     *
     * @return \Overtrue\EasySms\PhoneNumber
     */
    protected function formatPhoneNumber($number)
    {
        if ($number instanceof PhoneNumberInterface) {
            return $number;
        }

        return new \Overtrue\EasySms\PhoneNumber(trim($number));
    }

    /**
     * @param array|string|\Overtrue\EasySms\Contracts\MessageInterface $message
     *
     * @return \Overtrue\EasySms\Contracts\MessageInterface
     */
    protected function formatMessage($message)
    {
        if (! ($message instanceof MessageInterface)) {
            if (! is_array($message)) {
                $message = [
                    'content' => $message,
                    'template' => $message,
                ];
            }

            $message = new \Overtrue\EasySms\Message($message);
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
        try {
            $number = $this->formatPhoneNumber($number);
            $message = $this->formatMessage($message);

            $this->sending($number, $message);
            Log::debug(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent($this->driver), $message->getTemplate($this->driver), json_encode($message->getData($this->driver)), $message->getMessageType()));
            $result = $this->driver->send($number, $message, $this->config);
            Log::debug(sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent($this->driver), $message->getTemplate($this->driver), json_encode($message->getData($this->driver)), $message->getMessageType()), (array) $result);
            $this->sent($number, $message, $result);

            return $result;
        } catch (\Throwable $exception) {
            throw CouldNotSendNotification::captureExceptionInDriver($exception);
        }
    }

    public function sending($number, $message): void
    {
    }

    public function sent($number, $message, $result): void
    {
    }
}
