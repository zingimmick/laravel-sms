<?php

namespace Zing\LaravelSms\Contracts;

interface Driver
{
    public function send($number, $message);
}
