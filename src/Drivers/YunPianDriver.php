<?php

namespace Zing\LaravelSms\Drivers;

use Exception;
use Zing\LaravelSms\Exceptions\MessageSendErrorException;
use Zing\LaravelSms\Messages\SmsMessage;
use function strpos;

class YunPianDriver extends Driver
{
    protected function getBaseUri()
    {
        return 'http://yunpian.com';
    }

    public function send($number, SmsMessage $message)
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

            throw new MessageSendErrorException(data_get($result, 'msg'), data_get($result, 'code'));
        } catch (Exception $exception) {
            throw new MessageSendErrorException($exception->getMessage(), $exception->getCode());
        }
    }
}
