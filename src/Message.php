<?php

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Overtrue\EasySms\Contracts\MessageInterface;

/**
 * Class Message.
 */
class Message extends \Overtrue\EasySms\Message
{
    use Queueable;

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function text($content)
    {
        return (new static([],MessageInterface::TEXT_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function voice($content)
    {
        return (new static([],MessageInterface::VOICE_MESSAGE))->setContent($content);
    }

    /**
     * @param string|callable $template
     * @param array|callable $data
     *
     * @return \Zing\LaravelSms\Message
     */
    public static function fromTemplate($template, $data)
    {
        return static::text('')->setTemplate($template)->setData($data);
    }
}
