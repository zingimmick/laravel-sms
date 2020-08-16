<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Gateways;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;

class NullGateway extends Gateway
{
    public function send(PhoneNumberInterface $number, MessageInterface $message, Config $config)
    {
        return ['success' => true, 'msg' => 'ok'];
    }
}
