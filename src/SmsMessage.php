<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Message;

class SmsMessage extends Message
{
    use Queueable;

    public static function text(string|callable $content): self
    {
        return (new self([], MessageInterface::TEXT_MESSAGE))->setContent($content);
    }

    public static function voice(string|callable $content): self
    {
        return (new self([], MessageInterface::VOICE_MESSAGE))->setContent($content);
    }

    /**
     * @param mixed[]|callable $data
     */
    public static function fromTemplate(string|callable $template, array|callable $data): self
    {
        return static::text('')->setTemplate($template)->setData($data);
    }
}
