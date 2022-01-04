<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Gateways;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;

class LogGateway extends Gateway
{
    /**
     * @return array<string, string|bool>
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
    {
        $channel = $this->config->get('channel');
        $level = $this->config->get('level', 'info');
        Log::channel($channel)->{$level}(sprintf(
            'number: %s, message: "%s", template: "%s", data: %s, type: %s',
            $to,
            $message->getContent($this),
            $message->getTemplate($this),
            json_encode($message->getData($this)),
            $message->getMessageType()
        ));

        return [
            'success' => true,
            'msg' => 'ok',
        ];
    }
}
