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

    /**
     * @var string
     */
    private const ENDPOINT_URL = 'http://m.5c.com.cn/api/send/index.php';

    /**
     * @return array{success: true, msg: string, result: string}
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
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
                'mobile' => $to->getUniversalNumber(),
                'content' => strpos($content, '【') === 0 ? $content : $signature . $content,
            ]
        );
        if (! \is_string($result)) {
            throw new CouldNotSendNotification('meilian response does only seem to accept string.');
        }

        if (strpos($result, 'error') !== false) {
            throw new CouldNotSendNotification($result, 1, Arr::wrap($result));
        }

        return [
            'success' => true,
            'msg' => 'ok',
            'result' => $result,
        ];
    }
}
