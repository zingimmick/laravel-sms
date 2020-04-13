<?php

namespace Zing\LaravelSms\Drivers;

use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;

class YunpianDriver extends HttpDriver
{
    protected function getBaseUri()
    {
        return 'http://yunpian.com';
    }

    public function sendFormatted(PhoneNumber $number, Message $message)
    {
        $signature = $this->config->get('signature');
        $content = $message->getContent($this);
        $result = $this->post('/v1/sms/send.json', [
            'apikey' => $this->config['api_key'],
            'mobile' => $number->getUniversalNumber(),
            'text' => strpos($content, '【') === 0 ? $content : $signature . $content,
        ]);
        if (data_get($result, 'code') === 0) {
            return $result;
        }

        throw new CouldNotSendNotification(data_get($result, 'msg'), data_get($result, 'code'), $result);
    }
}
