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

    /**
     * @param \Illuminate\Notifications\Notification|null $notification
     */
    public function routeNotificationForSms($notification = null): string
    {
        if ($notification !== null) {
            return $this->phone;
        }

        return '';
    }
}
