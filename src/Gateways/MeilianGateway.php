<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Gateways;

use Illuminate\Support\Arr;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;

class MeilianGateway extends Gateway
{
    use HasHttpRequest;

    public const ENDPOINT_URL = 'http://m.5c.com.cn/api/send/index.php';

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     * @param \Overtrue\EasySms\Support\Config $config
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     *
     * @return array
     */
    public function send(PhoneNumberInterface $number, MessageInterface $message, Config $config)
    {
        $endpoint = self::ENDPOINT_URL;

        $signature = $this->config->get('signature', '');

        $content = $message->getContent($this);
        $result = $this->post(
            $endpoint,
            [
                'username' => $this->config->get('username'),
                'password' => $this->config->get('password'),
                'apikey' => $this->config->get('api_key'),
                'mobile' => $number->getUniversalNumber(),
                'content' => strpos($content, '【') === 0 ? $content : $signature . $content,
            ]
        );
        if (! is_string($result)) {
            throw new CouldNotSendNotification('meilian response does only seem to accept string.');
        }

        if (strpos($result, 'error') !== false) {
            throw new CouldNotSendNotification($result, 1, Arr::wrap($result));
        }

        return ['success' => true, 'msg' => 'ok', 'result' => $result];
    }
}
