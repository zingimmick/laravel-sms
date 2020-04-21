<?php

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Zing\LaravelSms\Contracts\Message as MessageContract;

/**
 * Class Message.
 */
class Message extends \Overtrue\EasySms\Message
{
    use Queueable;

    /** @var string */
    protected $type;

    /** @var string|callable */
    protected $content;

    /** @var string|callable */
    protected $template;

    /** @var array|callable */
    protected $data = [];

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function text($content)
    {
        return (new static([],MessageContract::TEXT))->setContent($content);
    }

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function voice($content)
    {
        return (new static([],MessageContract::VOICE))->setContent($content);
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
