<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;

class DummyDriver extends Gateway
{
    public function send(PhoneNumberInterface $number, MessageInterface $message, Config $config): void
    {
    }
}
