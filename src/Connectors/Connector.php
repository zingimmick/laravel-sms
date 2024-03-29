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
use Zing\LaravelSms\Events\SmsSending;
use Zing\LaravelSms\Events\SmsSent;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Exceptions\InvalidArgumentException;

class Connector implements ConnectorInterface
{
    /**
     * @var \Overtrue\EasySms\Contracts\GatewayInterface
     */
    protected $driver;

    protected Config $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function connect(array $config): self
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        $driverClass = $config['driver'];
        $this->driver = $this->resolveDriver($driverClass, $config);

        return $this;
    }

    /**
     * Get gateway by class name.
     *
     * @param class-string<\Overtrue\EasySms\Contracts\GatewayInterface> $driverClass
     * @param array<string, mixed> $config
     */
    protected function resolveDriver(string $driverClass, array $config): GatewayInterface
    {
        if (
            ! class_exists($driverClass) || ! \in_array(
                GatewayInterface::class,
                (array) class_implements($driverClass),
                true
            )
        ) {
            throw new InvalidArgumentException(sprintf('Unsupported driver [%s].', $driverClass));
        }

        return new $driverClass($config);
    }

    protected function formatPhoneNumber(PhoneNumberInterface|string $number): PhoneNumberInterface
    {
        if ($number instanceof PhoneNumberInterface) {
            return $number;
        }

        return new PhoneNumber(trim($number));
    }

    /**
     * @param array<string, mixed>|string|\Overtrue\EasySms\Contracts\MessageInterface $message
     */
    protected function formatMessage(array|MessageInterface|string $message): MessageInterface
    {
        if (! $message instanceof MessageInterface) {
            if (\is_string($message)) {
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
     * @param array<string, mixed>|string|\Overtrue\EasySms\Contracts\MessageInterface $message
     */
    public function send(PhoneNumberInterface|string $number, array|MessageInterface|string $message): mixed
    {
        $number = $this->formatPhoneNumber($number);
        $message = $this->formatMessage($message);

        try {
            Event::dispatch(new SmsSending($number, $message));
            $content = sprintf(
                'number: %s, message: "%s", template: "%s", data: %s, type: %s',
                $number,
                $message->getContent($this->driver),
                $message->getTemplate($this->driver),
                json_encode($message->getData($this->driver), JSON_THROW_ON_ERROR),
                $message->getMessageType()
            );
            Log::debug($content);
            $result = $this->driver->send($number, $message, $this->config);
            Log::debug($content, $result);
            Event::dispatch(new SmsSent($number, $message, $result));

            return $result;
        } catch (\Throwable $throwable) {
            throw CouldNotSendNotification::captureExceptionInDriver($throwable);
        }
    }
}
