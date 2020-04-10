<?php

namespace Zing\LaravelSms\Messages;

use Illuminate\Bus\Queueable;

class SmsMessage
{
    use Queueable;

    public const TEXT = 'text';

    public const VOICE = 'voice';

    protected $type;

    /** @var string|callable */
    protected $content;

    /** @var string|callable */
    protected $template;

    /** @var array|callable */
    protected $data = [];

    /**
     * SmsMessage constructor.
     *
     * @param $type
     * @param callable|string $content
     */
    public function __construct($type, $content)
    {
        $this->type = $type;
        $this->content = $content;
    }

    public static function text($content)
    {
        return new static(self::TEXT, $content);
    }

    public static function voice($content)
    {
        return new static(self::VOICE, $content);
    }

    public function withContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent($gateway = null)
    {
        return $this->retrieveValue($this->content, $gateway);
    }

    public function withTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate($gateway)
    {
        return $this->retrieveValue($this->template, $gateway);
    }

    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData($gateway)
    {
        return $this->retrieveValue($this->data, $gateway);
    }

    /**
     * @param callable|mixed $property
     * @param null $gateway
     *
     * @return mixed
     */
    protected function retrieveValue($property, $gateway)
    {
        if ($this->useAsCallable($property)) {
            return $property($gateway);
        }

        return $property;
    }

    protected function useAsCallable($value)
    {
        return ! is_string($value) && is_callable($value);
    }
}
