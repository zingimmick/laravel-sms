<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Exceptions;

use Throwable;

class CouldNotSendNotification extends Exception
{
    /**
     * @param mixed[] $raw
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        public array $raw = []
    ) {
        parent::__construct($message, $code);
    }

    public static function captureExceptionInDriver(Throwable $exception): self
    {
        if ($exception instanceof self) {
            return $exception;
        }

        return new self($exception->getMessage(), $exception->getCode());
    }
}
