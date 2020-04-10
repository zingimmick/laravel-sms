<?php

namespace Zing\LaravelSms\Exceptions;

use Exception;

class MessageSendErrorException extends Exception
{
    public $raw = [];

    public function __construct($message = '', $code = 0, $raw = [])
    {
        parent::__construct($message, $code);
        $this->raw = $raw;
    }
}
