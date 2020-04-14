<?php

namespace Zing\LaravelSms;

use Illuminate\Bus\Queueable;
use Zing\LaravelSms\Contracts\Message as MessageContract;

/**
 * Class Message.
 */
class Message implements MessageContract
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

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function text($content)
    {
        return new static(MessageContract::TEXT, $content);
    }

    /**
     * @param string|callable $content
     *
     * @return static
     */
    public static function voice($content)
    {
        return new static(MessageContract::VOICE, $content);
    }

    /**
     * @param string|callable $template
     * @param array|callable $data
     *
     * @return \Zing\LaravelSms\Message
     */
    public static function fromTemplate($template, $data)
    {
        return static::text('')->withTemplate($template)->withData($data);
    }

    /**
     * @param string|callable $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param \Zing\LaravelSms\Contracts\Driver $gateway
     *
     * @return string|null
     */
    public function getContent($gateway = null): ?string
    {
        return $this->retrieveValue($this->content, $gateway);
    }

    /**
     * @param string|callable $template
     *
     * @return $this
     */
    public function withTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param \Zing\LaravelSms\Contracts\Driver $gateway
     *
     * @return string|null
     */
    public function getTemplate($gateway = null): ?string
    {
        return $this->retrieveValue($this->template, $gateway);
    }

    /**
     * @param array|callable $data
     *
     * @return $this
     */
    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param \Zing\LaravelSms\Contracts\Driver $gateway
     *
     * @return array|null
     */
    public function getData($gateway = null): ?array
    {
        return $this->retrieveValue($this->data, $gateway);
    }

    /**
     * @param callable|mixed $property
     * @param \Zing\LaravelSms\Contracts\Driver $gateway
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

    /**
     * @param callable|mixed $value
     *
     * @return bool
     */
    protected function useAsCallable($value): bool
    {
        return ! is_string($value) && is_callable($value);
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getContent() ?: $this->toJson();
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'content' => $this->getContent(),
            'template' => $this->getTemplate(),
            'data' => $this->getData(),
        ];
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
