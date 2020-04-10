<?php


namespace Zing\LaravelSms\Contracts;


use Zing\LaravelSms\Contracts\Message as MessageContract;
use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;

interface Driver
{
    public function send($number, $message);
}