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

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public function routeNotificationForSms(?\Illuminate\Notifications\Notification $notification = null): string
    {
        if ($notification !== null) {
            return $this->phone;
        }

        return '';
    }
}
