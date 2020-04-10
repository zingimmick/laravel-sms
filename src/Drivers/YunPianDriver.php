<?php

namespace Zing\LaravelSms\Drivers;

use Exception;
use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;
use Zing\LaravelSms\Exceptions\CannotSendNotification;

class YunPianDriver extends Driver
{
    protected function getBaseUri()
    {
        return 'http://yunpian.com';
    }

    public function sendMessage(PhoneNumber $number, Message $message)
    {
        try {
            $signature = $this->config->get('signature');
            $content = $message->getContent($this);
            $result = $this->post('/v1/sms/send.json', [
                'apikey' => $this->config['api_key'],
                'mobile' => $number,
                'text' => strpos($content, '【') === 0 ? $content : $signature . $content,
            ]);
            if (data_get($result, 'code') === 0) {
                return true;
            }

            throw new CannotSendNotification(data_get($result, 'msg'), data_get($result, 'code'));
        } catch (Exception $exception) {
            throw new CannotSendNotification($exception->getMessage(), $exception->getCode());
        }
    }
}
