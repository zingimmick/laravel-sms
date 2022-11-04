<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Gateways;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;

class YunpianGateway extends Gateway
{
    use HasHttpRequest;

    public function getBaseUri(): string
    {
        return 'http://yunpian.com';
    }

    /**
     * @return mixed[]
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
    {
        $signature = $this->config->get('signature');
        $content = $message->getContent($this);

        /** @var mixed[] $result */
        $result = $this->post(
            '/v1/sms/send.json',
            [
                'apikey' => $this->config['api_key'],
                'mobile' => $to->getUniversalNumber(),
                'text' => str_starts_with($content, '【') ? $content : $signature . $content,
            ]
        );
        if (data_get($result, 'code') === 0) {
            return $result;
        }

        throw new CouldNotSendNotification(data_get($result, 'msg'), data_get($result, 'code'), $result);
    }
}
