<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Message;

class SmsMessage extends Message
{
    use Queueable;

    /**
     * @param string|callable $content
     */
    public static function text($content): self
    {
        return (new self([], MessageInterface::TEXT_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $content
     */
    public static function voice($content): self
    {
        return (new self([], MessageInterface::VOICE_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $template
     * @param array|callable $data
     *
     * @return static
     */
    public static function fromTemplate($template, $data): self
    {
        return static::text('')->setTemplate($template)->setData($data);
    }
}
