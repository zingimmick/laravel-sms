<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Exceptions;

use Throwable;

class CouldNotSendNotification extends Exception
{
    /**
     * @var mixed[]
     */
    public $raw = [];

    public function __construct($message = '', $code = 0, $raw = [])
    {
        parent::__construct($message, $code);

        $this->raw = $raw;
    }

    public static function captureExceptionInDriver(Throwable $exception): self
    {
        if ($exception instanceof self) {
            return $exception;
        }

        return new self($exception->getMessage(), $exception->getCode());
    }
}
