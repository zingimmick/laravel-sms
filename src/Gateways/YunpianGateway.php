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

    public function getBaseUri()
    {
        return 'http://yunpian.com';
    }

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $to
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     * @param \Overtrue\EasySms\Support\Config $config
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     *
     * @return array
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $signature = $this->config->get('signature');
        $content = $message->getContent($this);
        $result = $this->post(
            '/v1/sms/send.json',
            [
                'apikey' => $this->config['api_key'],
                'mobile' => $to->getUniversalNumber(),
                'text' => strpos($content, '【') === 0 ? $content : $signature . $content,
            ]
        );
        if (data_get($result, 'code') === 0) {
            return $result;
        }

        throw new CouldNotSendNotification(data_get($result, 'msg'), data_get($result, 'code'), $result);
    }
}
