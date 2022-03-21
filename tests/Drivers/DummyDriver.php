<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;

class DummyDriver extends Gateway
{
    /**
     * @return array{success: true, msg: string}
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
    {
        return [
            'success' => true,
            'msg' => 'ok',
        ];
    }
}
