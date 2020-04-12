<?php

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Zing\LaravelSms\Contracts\Message as MessageContract;

class Message implements MessageContract
{
    use Queueable;

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
        return new static(MessageContract::TEXT, $content);
    }

    public static function voice($content)
    {
        return new static(MessageContract::VOICE, $content);
    }

    public static function fromTemplate($template, $data)
    {
        return static::text('')->withTemplate($template)->withData($data);
    }

    public function withContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent($gateway = null): ?string
    {
        return $this->retrieveValue($this->content, $gateway);
    }

    public function withTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate($gateway = null): ?string
    {
        return $this->retrieveValue($this->template, $gateway);
    }

    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData($gateway = null): ?array
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

    protected function useAsCallable($value): bool
    {
        return ! is_string($value) && is_callable($value);
    }

    public function __toString()
    {
        return $this->getContent() ?: $this->toJson();
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'content' => $this->getContent(),
            'template' => $this->getTemplate(),
            'data' => $this->getData(),
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
