<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notifiable;

class Phone
{
    use Notifiable;

    public function __construct(protected string $phone)
    {
    }

    public function routeNotificationForSms(?\Illuminate\Notifications\Notification $notification = null): string
    {
        if ($notification !== null) {
            return $this->phone;
        }

        return '';
    }
}
