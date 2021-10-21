<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Gateways;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;

class NullGateway extends Gateway
{
    /**
     * @return array<string, string>|array<string, bool>
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
    {
        return [
            'success' => true,
            'msg' => 'ok',
        ];
    }
}
