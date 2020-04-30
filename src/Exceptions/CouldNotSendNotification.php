<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Exceptions;

use Throwable;

class CouldNotSendNotification extends Exception
{
    public $raw = [];

    public function __construct($message = '', $code = 0, $raw = [])
    {
        parent::__construct($message, $code);
        $this->raw = $raw;
    }

    public static function captureExceptionInDriver(Throwable $exception)
    {
        if ($exception instanceof static) {
            return $exception;
        }

        return new static($exception->getMessage(), $exception->getCode());
    }
}
