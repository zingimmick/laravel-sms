<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Support\Config;

abstract class LocalDriver extends Driver
{
    public function __construct($config = null)
    {
        $this->config = new Config($config);
    }
}
