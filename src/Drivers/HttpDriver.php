<?php


namespace Zing\LaravelSms\Drivers;


use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Support\HasHttpRequest;

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