<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notifiable;

class Phone
{
    use Notifiable;

    /**
     * @var string
     */
    protected $phone;

    /**
     * Phone constructor.
     *
     * @param string $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    public function getKey(): string
    {
        return $this->phone;
    }

    /**
     * @param mixed $notification
     *
     * @return mixed|string
     */
    public function routeNotificationForSms($notification)
    {
        if ($notification) {
            return $this->phone;
        }

        return '';
    }
}
