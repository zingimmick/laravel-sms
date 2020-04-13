<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Concerns\HasHttpRequest;

abstract class HttpDriver extends LocalDriver
{
    use HasHttpRequest;

    public $client;

    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->client = $this->getHttpClient($this->getBaseOptions());
    }
}
