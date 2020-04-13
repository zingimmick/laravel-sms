<?php

namespace Zing\LaravelSms\Drivers;

use Illuminate\Support\Arr;
use Zing\LaravelSms\Contracts\Message;
use Zing\LaravelSms\Contracts\PhoneNumber;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;

class MeilianDriver extends HttpDriver
{
    public const ENDPOINT_URL = 'http://m.5c.com.cn/api/send/index.php';

    /**
     * @param PhoneNumber $to
     * @param Message $message
     *
     * @return array
     *
     * @throws \Zing\LaravelSms\Exceptions\CouldNotSendNotification
     */
    public function sendFormatted(PhoneNumber $to, Message $message)
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
        if (strpos($result, 'error') !== false) {
            throw new CouldNotSendNotification($result, 1, Arr::wrap($result));
        }

        return $result;
    }
}
