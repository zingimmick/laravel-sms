<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Phone
{
    use Notifiable;

    public function __construct(
        protected string $phone
    ) {
    }

    public function routeNotificationForSms(?Notification $notification = null): string
    {
        if ($notification instanceof \Illuminate\Notifications\Notification) {
            return $this->phone;
        }

        return '';
    }
}
