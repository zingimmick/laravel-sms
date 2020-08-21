<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Message;

/**
 * Class Message.
 */
class SmsMessage extends Message
{
    use Queueable;

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function text($content)
    {
        return (new self([], MessageInterface::TEXT_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function voice($content)
    {
        return (new self([], MessageInterface::VOICE_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $template
     * @param array|callable $data
     *
     * @return static
     */
    public static function fromTemplate($template, $data)
    {
        return static::text('')->setTemplate($template)->setData($data);
    }
}
